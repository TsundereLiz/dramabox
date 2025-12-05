<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API License Expired - DramaBox</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1a1d23;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .error-container {
            max-width: 600px;
            text-align: center;
        }

        .error-icon {
            font-size: 6rem;
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #ff6b6b;
        }

        p {
            font-size: 1.1rem;
            color: #b8bcc4;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #4a9eff;
            color: white;
        }

        .btn-primary:hover {
            background: #3a8eef;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 158, 255, 0.4);
        }

        .btn-secondary {
            background: #292f36;
            color: white;
            border: 2px solid #3d4451;
        }

        .btn-secondary:hover {
            background: #3d4451;
            transform: translateY(-2px);
        }

        .info-box {
            background: #292f36;
            border: 1px solid #3d4451;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: left;
        }

        .info-box h3 {
            color: #4a9eff;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
        }

        .info-box li {
            padding: 0.5rem 0;
            color: #b8bcc4;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box li:before {
            content: "✓";
            color: #4a9eff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔒</div>
        <h1>API License Expired</h1>
        <p>
            Maaf, license API Anda telah expired atau tidak valid. 
            Silakan hubungi kami untuk mendapatkan license baru atau perpanjang license yang sudah ada.
        </p>

        <div class="buttons">
            <a href="https://dramaboxapi.web.id/" class="btn btn-primary" target="_blank">
                🌐 Get New License
            </a>
            <a href="https://t.me/bitvideo_online" class="btn btn-secondary" target="_blank">
                💬 Contact Telegram
            </a>
        </div>

        <div class="info-box">
            <h3>📌 Informasi License</h3>
            <ul>
                <li>License gratis tersedia untuk testing</li>
                <li>License premium untuk production</li>
                <li>Support 24/7 via Telegram</li>
            </ul>
        </div>
    </div>
</body>
</html>
