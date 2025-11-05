<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Database Connection Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 550px;
            width: 100%;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .error-icon {
            font-size: 64px;
            margin-bottom: 15px;
            display: block;
        }

        .error-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            border: none;
        }

        .error-body {
            padding: 35px 30px;
        }

        .error-title {
            color: #e74c3c;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-title i {
            font-size: 22px;
        }

        .error-message {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .error-message code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #495057;
            display: inline-block;
            margin: 2px 0;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #4e73df;
            color: white;
        }

        .btn-primary:hover {
            background: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #4e73df;
            color: #4e73df;
        }

        .btn-outline:hover {
            background: #4e73df;
            color: white;
        }

        .error-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #6c757d;
        }

        .technical-details {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
        }

        .technical-details summary {
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 10px;
        }

        @media (max-width: 576px) {
            .error-container {
                margin: 10px;
            }
            
            .error-header {
                padding: 25px 20px;
            }
            
            .error-body {
                padding: 25px 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <i class="fas fa-database error-icon"></i>
            <h1>Database Connection Error</h1>
        </div>
        
        <div class="error-body">
            <div class="error-title">
                <i class="fas fa-exclamation-triangle"></i>
                Database Connection Failed
            </div>
            
            <p>Maaf, sistem sedang mengalami gangguan koneksi database. Hal ini mungkin disebabkan oleh:</p>
            
            <div class="error-message">
                <?php echo $message; ?>
            </div>

            <details class="technical-details">
                <summary>Detail Teknis (Untuk Developer)</summary>
                <div style="margin-top: 10px;">
                    <strong>Heading:</strong> <?php echo $heading; ?><br>
                    <strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?><br>
                    <strong>URL:</strong> <code><?php echo (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A'); ?></code>
                </div>
            </details>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
                
                <a href="<?php echo base_url(); ?>" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Kembali ke Home
                </a>
                
                <button class="btn btn-outline" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
                </button>
            </div>
        </div>
        
        <div class="error-footer">
            <i class="fas fa-info-circle"></i> 
            Jika masalah berlanjut, hubungi administrator sistem.
            <br>
            <small>Error ID: <?php echo uniqid(); ?></small>
        </div>
    </div>

    <script>
        // Auto refresh setelah 30 detik
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Tambahkan efek saat hover button
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>