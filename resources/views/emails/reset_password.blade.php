<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Posyandu Lansia</title>
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
            background-color: #dc3545;
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
        
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            color: #856404;
        }
        
        .alert-box .icon {
            display: inline-block;
            margin-right: 10px;
            font-size: 20px;
        }
        
        .alert-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .credentials-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .credentials-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .credentials-title .icon {
            margin-right: 10px;
            font-size: 20px;
        }
        
        .credential-item {
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .credential-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 20px 8px 20px;
            background-color: #f9fafb;
            margin: 0;
        }
        
        .credential-value {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 12px 20px 16px 20px;
            margin: 0;
            border-radius: 0 0 6px 6px;
            word-break: break-all;
        }
        
        .password-value {
            color: #dc2626;
            background-color: #fef2f2;
        }
        
        .security-warning {
            background-color: #d1ecf1;
            border: 1px solid #b8daff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            color: #0c5460;
        }
        
        .security-warning .icon {
            display: inline-block;
            margin-right: 10px;
            font-size: 18px;
        }
        
        .security-warning strong {
            color: #084c61;
        }
        
        .action-steps {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .action-steps h3 {
            color: #155724;
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .action-steps h3 .icon {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .step-list {
            list-style: none;
            padding: 0;
        }
        
        .step-list li {
            padding: 12px 0;
            color: #155724;
            display: flex;
            align-items: center;
        }
        
        .step-list li .step-number {
            background-color: #28a745;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            margin-right: 15px;
            flex-shrink: 0;
            line-height: 1;
            text-align: center;
        }
        
        .step-list li span:last-child {
            flex: 1;
            line-height: 1.5;
        }
        
        .login-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            margin: 20px 0;
        }
        
        .login-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .button-container a {
            color: white;
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
            color: #dc3545;
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
            
            .credentials-box {
                padding: 20px 15px;
            }
            
            .credential-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .credential-value {
                width: 100%;
                text-align: center;
            }
            
            .login-button {
                padding: 14px 24px;
                font-size: 15px;
            }
            
            .footer {
                padding: 20px;
            }
            
            .step-list li .step-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
                margin-right: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p class="subtitle">Sistem Posyandu Lansia</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="alert-box">
                <div class="alert-title">
                    <span class="icon">⚠️</span>
                    Password Berhasil Di-Reset
                </div>
                <div>Permintaan reset password Anda telah diproses. Kredensial login baru telah dibuat.</div>
            </div>
            
            <div class="message">
                Password Anda telah berhasil di-reset. Berikut adalah kredensial login baru yang dapat Anda gunakan untuk mengakses sistem:
            </div>
            
            <!-- Credentials Box -->
            <div class="credentials-box">
                <div class="credentials-title">
                    <span class="icon">🔑</span>
                    Kredensial Login Baru
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Username</div>
                    <div class="credential-value">{{ $username }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Password Baru</div>
                    <div class="credential-value password-value">{{ $newPassword }}</div>
                </div>
            </div>
            
            <!-- Security Warning -->
            <div class="security-warning">
                <span class="icon">🛡️</span>
                <strong>Penting untuk Keamanan:</strong><br>
                Password ini bersifat sementara dan dibuat secara otomatis. Segera ganti password ini dengan yang lebih mudah Anda ingat setelah login pertama kali.
            </div>
            
            <!-- Action Steps -->
            <div class="action-steps">
                <h3>
                    <span class="icon">📝</span>
                    Langkah Selanjutnya
                </h3>
                <ul class="step-list">
                    <li>
                        <span class="step-number">1</span>
                        <span>Login menggunakan kredensial di atas</span>
                    </li>
                    <li>
                        <span class="step-number">2</span>
                        <span>Segera ganti password dengan yang baru</span>
                    </li>
                    <li>
                        <span class="step-number">3</span>
                        <span>Gunakan password yang kuat dan mudah diingat</span>
                    </li>
                    <li>
                        <span class="step-number">4</span>
                        <span>Jangan bagikan kredensial kepada siapapun</span>
                    </li>
                </ul>
            </div>
            
            <div class="button-container">
                <a href="{{ route('login') }}" class="login-button">
                    🚀 Login Sekarang
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                Email ini dikirim secara otomatis karena ada permintaan reset password.
            </div>
            <div class="footer-text">
                Jika Anda tidak merasa melakukan reset password, segera hubungi administrator.
            </div>
            <div class="footer-brand">
                Sistem Posyandu Lansia
            </div>
        </div>
    </div>
</body>
</html>