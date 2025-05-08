<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Notification | XRAY Shop</title>
</head>
<body style="background-color: #e7eff8; font-family: Trebuchet, sans-serif; margin: 0; padding: 0; line-height: 1.5;">
<div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
    <!-- Header -->
    <div style="background-color: #00509d; padding: 20px; text-align: center; color: white; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 32px;">XRAY Shop</h1>
        <p style="margin: 0;">New Contact Message</p>
    </div>

    <!-- Body -->
    <div style="padding: 20px;">
        <h2 style="color: #333;">Contact Details</h2>
        <p><strong>Name:</strong> {{ $name }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $email }}" style="color: #00509d;">{{ $email }}</a></p>
        <p><strong>Subject:</strong> {{ $subject }}</p>

        <hr style="margin: 20px 0;">

        <h3 style="color: #333;">Message</h3>
        <p style="white-space: pre-line;">{{ $message_body }}</p>
    </div>

    <!-- Footer -->
    <div style="background-color: #f4f8fd; text-align: center; padding: 10px; font-size: 14px; border-radius: 0 0 8px 8px;">
        <p style="margin: 0;">Thank you for contacting XRAY Shop.</p>
    </div>
</div>
</body>
</html>
