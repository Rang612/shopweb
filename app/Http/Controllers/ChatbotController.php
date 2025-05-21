<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use App\Models\Product;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $userMessage = $request->input('message');

            $client = OpenAI::client(env('OPENAI_API_KEY'));

            // 1. Tạo thread
            $thread = $client->threads()->create([]);

            // 2. Gửi message của người dùng vào thread
            $client->threads()->messages()->create($thread->id, [
                'role' => 'user',
                'content' => $userMessage,
            ]);

            // 3. Tạo run
            $run = $client->threads()->runs()->create($thread->id, [
                'assistant_id' => env('OPENAI_ASSISTANT_ID'),
            ]);

            // 4. Đợi assistant xử lý
            $maxAttempts = 10;
            $attempt = 0;
            do {
                sleep(1);
                $run = $client->threads()->runs()->retrieve($thread->id, $run->id);
                $attempt++;
           } while (!in_array($run->status, ['completed', 'requires_action']) && $attempt < $maxAttempts);

            if ($attempt >= $maxAttempts) {
                return response()->json(['reply' => 'Timeout while processing request.'], 500);
            }

            // 5. Nếu Assistant yêu cầu gọi hàm
            if ($run->status === 'requires_action') {
                // Extract tool_calls with detailed debugging
                $requiredAction = $run->requiredAction; // Changed from $run->required_action to $run->requiredAction

                // Convert required_action to array for easier debugging
                $requiredActionArray = json_decode(json_encode($requiredAction), true);

                $toolCalls = [];
                // Try accessing tool_calls in multiple ways to handle SDK inconsistencies
                if (isset($requiredAction->submitToolOutputs) && property_exists($requiredAction->submitToolOutputs, 'toolCalls')) {
                    $toolCalls = $requiredAction->submitToolOutputs->toolCalls;
                } elseif (isset($requiredActionArray['submitToolOutputs']['toolCalls'])) {
                    $toolCalls = $requiredActionArray['submitToolOutputs']['toolCalls'];
                } elseif (isset($requiredAction->submitToolOutputs) && is_array($requiredAction->submitToolOutputs)) {
                    $toolCalls = $requiredAction->submitToolOutputs['tool_calls'] ?? [];
                }

                if (empty($toolCalls)) {
                    return response()->json(['reply' => 'No products found. Please try a different query.']);
                }

                $toolOutputs = [];
                foreach ($toolCalls as $toolCall) {
                    // Handle tool_call as array or object
                    $function = is_array($toolCall) ? ($toolCall['function'] ?? []) : ($toolCall->function ?? null);
                    $toolCallId = is_array($toolCall) ? ($toolCall['id'] ?? null) : ($toolCall->id ?? null);

                    if (!$function || !$toolCallId) {
                        continue;
                    }
                    if (is_object($function)) {
                        $functionName = $function->name ?? '';
                        $arguments = json_decode($function->arguments ?? '{}', true);
                    } else {
                        $functionName = $function['name'] ?? '';
                        $arguments = json_decode($function['arguments'] ?? '{}', true);
                    }
                    if ($functionName === 'search_products') {
                        // Truy vấn sản phẩm
                        $products = Product::query()
                            ->when($arguments['category'] ?? null, fn($q) => $q->whereHas('productcategory', fn($c) =>
                            $c->where('name', 'like', "%{$arguments['category']}%")))
                            ->when($arguments['sub_category'] ?? null, fn($q) => $q->whereHas('subCategory', fn($s) =>
                            $s->where('name', 'like', "%{$arguments['sub_category']}%")))
                            ->when($arguments['brand'] ?? null, fn($q) => $q->whereHas('brand', fn($b) =>
                            $b->where('name', 'like', "%{$arguments['brand']}%")))
                            ->when($arguments['tag'] ?? null, fn($q) => $q->whereHas('tags', fn($t) =>
                            $t->where('name', 'like', "%{$arguments['tag']}%")))
                            ->when($arguments['size'] ?? null, fn($q) => $q->whereHas('productDetail', fn($d) =>
                            $d->where('size', 'like', "%{$arguments['size']}%")))
                            ->when($arguments['color'] ?? null, fn($q) => $q->whereHas('productDetail', fn($d) =>
                            $d->where('color', 'like', "%{$arguments['color']}%")))
                            ->when($arguments['price_max'] ?? null, fn($q) =>
                            $q->where('compare_price', '<=', $arguments['price_max']))
                            ->with('productImages')
                            ->take(5)
                            ->get();
                        // Render HTML
                        if ($products->isEmpty()) {
                            $toolOutputs[] = [
                                'tool_call_id' => $toolCallId,
                                'output' => json_encode(['reply' => 'No products found.'])
                            ];
                            continue;
                        }

                        $html = '';
                        foreach ($products as $p) {
                            $img = $p->productImages->first();
                            $imagePath = 'uploads/products/large/' . ($img->image ?? '');
                            $imageUrl = (isset($img->image) && file_exists(public_path($imagePath)))
                                ? asset($imagePath)
                                : asset('front/img/default.jpg');
                            $price = number_format($p->compare_price, 0, ',', '.');

                            $html .= <<<HTML
<div style="margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
    <img src="{$imageUrl}" alt="{$p->title}" style="width: 100px;"><br>
    <strong>{$p->title}</strong><br>
    Price: <span style="color: red;">{$price}đ</span><br>
    <a href="/shop/product/{$p->id}" target="_blank">View details</a>
</div>
HTML;
                        }

                        $toolOutputs[] = [
                            'tool_call_id' => $toolCallId,
                            'output' => json_encode(['reply' => $html])
                        ];
                    }
                }

                // Gửi tool outputs về OpenAI
                if (!empty($toolOutputs)) {
                    $client->threads()->runs()->submitToolOutputs($thread->id, $run->id, [
                        'tool_outputs' => $toolOutputs
                    ]);

                    // Đợi run hoàn tất
                    $attempt = 0;
                    do {
                        sleep(1);
                        $run = $client->threads()->runs()->retrieve($thread->id, $run->id);
                        $attempt++;
                    } while ($run->status !== 'completed' && $attempt < $maxAttempts);

                    if ($run->status === 'completed') {
                        $messages = $client->threads()->messages()->list($thread->id);
                        $reply = $messages->data[0]->content[0]->text->value ?? $toolOutputs[0]['output'];
                        return response()->json(['reply' => $reply]);
                    } else {
                        return response()->json(['reply' => 'Error processing product search.'], 500);
                    }
                }
            }

            // 6. Nếu không gọi function, lấy reply dạng text
            $messages = $client->threads()->messages()->list($thread->id);
            $reply = $messages->data[0]->content[0]->text->value ?? 'No response from Assistant.';

            return response()->json(['reply' => nl2br(e($reply))]);

        } catch (\Throwable $e) {
            \Log::error('Assistant connection failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['reply' => 'Error connecting to assistant.'], 500);
        }
    }
}
