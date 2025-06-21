<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Sistem Posyandu Lansia</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .verify-button {
            display: inline-block;
            background-color: #6366f1;
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            margin: 20px 0;
            min-width: 250px;
            text: white;
        }
        
        .verify-button:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }
        
        .verify-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: #10b981;
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            vertical-align: middle;
        }
        
        .verify-icon::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
            color: white;
        }
        
        .alternative-text {
            font-size: 14px;
            color: #666;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .alternative-text strong {
            color: #333;
        }
        
        .link-text {
            word-break: break-all;
            color: #667eea;
            font-family: monospace;
            font-size: 13px;
            background-color: #f1f3f4;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .footer-brand {
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
        }
        
        .security-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }
        
        .security-note .icon {
            display: inline-block;
            margin-right: 8px;
            font-size: 16px;
        }
        
        /* Responsive design */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0 10px;
                border-radius: 8px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .verify-button {
                padding: 14px 24px;
                font-size: 15px;
            }
            
            .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🏥 Sistem Posyandu Lansia</h1>
            <p class="subtitle">Verifikasi Email Diperlukan</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo {{ $name }}, 👋
            </div>
            
            <div class="message">
                Terima kasih telah mendaftar di Sistem Posyandu Lansia! Untuk melengkapi proses pendaftaran Anda, silakan verifikasi alamat email ini dengan mengklik tombol di bawah.
            </div>
            
            <div class="button-container">
                <a href="{{ $verifyUrl }}" class="verify-button">
                    <span class="verify-icon"></span>Verifikasi Email Saya
                </a>
            </div>
            
            <div class="security-note">
                <span class="icon">🔒</span>
                <strong>Catatan Keamanan:</strong> Link verifikasi ini akan kedaluwarsa dalam 60 menit untuk menjaga keamanan akun Anda.
            </div>
            
            <div class="alternative-text">
                <strong>Tombol tidak berfungsi?</strong><br>
                Jika tombol di atas tidak dapat diklik, silakan salin dan tempel link berikut ke address bar browser Anda:
                <div class="link-text">{{ $verifyUrl }}</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                Email ini dikirim secara otomatis. Mohon jangan membalas email ini.
            </div>
            <div class="footer-text">
                Jika Anda tidak merasa mendaftar, abaikan email ini.
            </div>
            <div class="footer-brand">
                Sistem Posyandu Lansia
            </div>
        </div>
    </div>
</body>
</html>