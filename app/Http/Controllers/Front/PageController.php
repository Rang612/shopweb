<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
//    public function contact(){
//        return view('front.contact');
//    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if($page == null){
            abort(404);
        }
        return view('front.page.page', compact('page'));
    }

    public function sendContactUsEmail(Request $request){

        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Thông tin gửi
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message_body' => $request->message,
        ];

        // Gửi mail
        Mail::send('front.page.email', $data, function($message) use ($data) {
            $message->to('tranhuonggiang6122003@gmail.com') // Email nhận
            ->subject('Contact from: ' . $data['name']);
        });

        // Trả về response hoặc redirect kèm thông báo
        return back()->with('success', 'Your message has been sent successfully!');
    }

}
