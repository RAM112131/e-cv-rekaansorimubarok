<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Pesan</title>
    <style>
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.7;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 30px 15px;
        }
        .wrapper {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 24px 32px;
            color: #ffffff;
        }
        .header-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #f8fafc;
        }
        .content-body {
            padding: 32px;
        }
        .salutation {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .intro-text {
            color: #475569;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .reply-text {
            font-size: 15px;
            color: #1e293b;
            white-space: pre-line;
            margin-bottom: 28px;
        }
        .original-quote {
            background-color: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 16px 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 28px;
        }
        .quote-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .quote-content {
            font-size: 14px;
            color: #334155;
            font-style: italic;
            margin: 0;
        }
        .signature-box {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 24px;
        }
        .closing {
            margin: 0 0 8px 0;
            color: #475569;
            font-size: 14px;
        }
        .sender-name {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .sender-title {
            margin: 2px 0 0 0;
            font-size: 13px;
            color: #64748b;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 16px 32px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header-bar">
            <h1 class="header-title">Personal Portfolio — Communication</h1>
        </div>

        <div class="content-body">
            <p class="salutation">{{ $salutation }}</p>

            <p class="intro-text">
                Terima kasih telah mengontak saya melalui website portofolio. Berikut adalah tanggapan atas pesan yang Anda sampaikan:
            </p>

            <div class="reply-text">{!! nl2br(e($replyContent)) !!}</div>

            @if(!empty($messageRecord->message))
            <div class="original-quote">
                <div class="quote-title">Pesan Anda sebelumnya ({{ $messageRecord->created_at?->format('d M Y, H:i') }}):</div>
                <p class="quote-content">"{{ $messageRecord->message }}"</p>
            </div>
            @endif

            <div class="signature-box">
                <p class="closing">{{ $closing }}</p>
                <p class="sender-name">{{ $senderName }}</p>
                <p class="sender-title">{{ $senderHeadline ?: 'Web Developer & Software Engineer' }}</p>
            </div>
        </div>

        <div class="footer">
            Email ini dikirimkan resmi sebagai balasan atas formulir kontak pada Website Portofolio.
        </div>
    </div>
</body>
</html>
