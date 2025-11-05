<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Application Exception</title>
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
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
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
            max-width: 800px;
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
            background: linear-gradient(135deg, #ff9a9e, #fecfef);
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
            padding: 30px;
        }

        .error-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #ff9a9e;
        }

        .error-title {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-detail {
            margin-bottom: 10px;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .error-detail strong {
            color: #495057;
            min-width: 120px;
            display: inline-block;
        }

        .backtrace {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 13px;
            max-height: 300px;
            overflow-y: auto;
        }

        .backtrace-item {
            padding: 8px;
            border-bottom: 1px solid #ffeaa7;
            font-family: 'Courier New', monospace;
        }

        .backtrace-item:last-child {
            border-bottom: none;
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

        .error-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #6c757d;
        }

        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 10px 0;
            overflow-x: auto;
        }

        @media (max-width: 576px) {
            .error-container {
                margin: 10px;
            }
            
            .error-header {
                padding: 25px 20px;
            }
            
            .error-body {
                padding: 20px;
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
            <i class="fas fa-bug error-icon"></i>
            <h1>Application Exception</h1>
        </div>
        
        <div class="error-body">
            <div class="error-section">
                <div class="error-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Uncaught Exception Details
                </div>
                
                <div class="error-detail">
                    <strong>Exception Type:</strong> <?php echo get_class($exception); ?>
                </div>
                <div class="error-detail">
                    <strong>Message:</strong> <?php echo $message; ?>
                </div>
                <div class="error-detail">
                    <strong>File:</strong> <?php echo $exception->getFile(); ?>
                </div>
                <div class="error-detail">
                    <strong>Line:</strong> <?php echo $exception->getLine(); ?>
                </div>
            </div>

            <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
            <div class="error-section">
                <div class="error-title">
                    <i class="fas fa-project-diagram"></i>
                    Stack Trace
                </div>
                
                <div class="backtrace">
                    <?php foreach ($exception->getTrace() as $error): ?>
                        <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
                            <div class="backtrace-item">
                                <strong>File:</strong> <?php echo $error['file']; ?><br>
                                <strong>Line:</strong> <?php echo $error['line']; ?><br>
                                <strong>Function:</strong> <?php echo $error['function']; ?>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif ?>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="fas fa-redo"></i> Reload Page
                </button>
                
                <a href="<?php echo base_url(); ?>" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                
                <button class="btn btn-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Go Back
                </button>
            </div>
        </div>
        
        <div class="error-footer">
            <i class="fas fa-info-circle"></i> 
            This error has been logged. Please contact development team if issue persists.
            <br>
            <small>Error ID: <?php echo uniqid(); ?> | Timestamp: <?php echo date('Y-m-d H:i:s'); ?></small>
        </div>
    </div>

    <script>
        // Auto refresh setelah 30 detik
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>