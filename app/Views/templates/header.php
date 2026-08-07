<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Car Rental System' ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Utility Classes */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .card {
            background: var(--surface);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--surface);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #f0fdf4;
            border-color: var(--success);
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border-color: var(--error);
            color: #991b1b;
        }

        .alert-warning {
            background: #fffbeb;
            border-color: var(--warning);
            color: #92400e;
        }

        .grid {
            display: grid;
            gap: 24px;
        }

        .grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .text-center {
            text-align: center;
        }

        .text-primary {
            color: var(--primary);
        }

        .text-secondary {
            color: var(--text-secondary);
        }

        .mb-4 {
            margin-bottom: 24px;
        }

        .mt-4 {
            margin-top: 24px;
        }

        .p-4 {
            padding: 24px;
        }

        /* Loading Spinner */
        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }
            
            .card {
                padding: 20px;
            }
        }

        /* In header.php - add this to the <style> section */
[data-theme="dark"] {
    --primary: #3b82f6;
    --primary-dark: #2563eb;
    --secondary: #94a3b8;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --background: #0f172a;
    --surface: #1e293b;
    --text-primary: #f1f5f9;
    --text-secondary: #cbd5e1;
    --border: #334155;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
}

[data-theme="dark"] .card {
    background: var(--surface);
    border-color: var(--border);
}

[data-theme="dark"] .form-control {
    background: #1e293b;
    border-color: #475569;
    color: var(--text-primary);
}

[data-theme="dark"] .form-control:focus {
    background: #1e293b;
    border-color: var(--primary);
}

[data-theme="dark"] .btn-outline {
    border-color: #475569;
    color: var(--text-primary);
}

[data-theme="dark"] .btn-outline:hover {
    background: #334155;
    border-color: var(--primary);
}

[data-theme="dark"] .btn-primary {
    background: var(--primary);
    color: white;
}

[data-theme="dark"] select.form-control {
    background: #1e293b;
    color: var(--text-primary);
}

[data-theme="dark"] option {
    background: #1e293b;
    color: var(--text-primary);
}

[data-theme="dark"] table {
    color: var(--text-primary);
}

[data-theme="dark"] .status-badge {
    color: white !important;
}

/* Additional dark theme styles for login/register */
[data-theme="dark"] .glass-card {
    background: rgba(30, 41, 59, 0.85) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

[data-theme="dark"] .auth-form input,
[data-theme="dark"] .auth-form textarea,
[data-theme="dark"] .login-form input {
    background: rgba(15, 23, 42, 0.8) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: var(--text-primary) !important;
}

[data-theme="dark"] .auth-form input:focus,
[data-theme="dark"] .auth-form textarea:focus,
[data-theme="dark"] .login-form input:focus {
    background: rgba(15, 23, 42, 0.95) !important;
    border-color: var(--primary) !important;
}

[data-theme="dark"] .input-icon {
    color: var(--text-secondary) !important;
}

[data-theme="dark"] .card-header h1,
[data-theme="dark"] .card-header p,
[data-theme="dark"] .input-group label {
    color: var(--text-primary) !important;
}

[data-theme="dark"] .label-text {
    color: var(--text-secondary) !important;
}

[data-theme="dark"] .label-text a {
    color: var(--primary) !important;
}

[data-theme="dark"] .divider::before,
[data-theme="dark"] .divider::after {
    background: rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .alert {
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
}

[data-theme="dark"] .alert-error {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #fca5a5 !important;
}

[data-theme="dark"] .alert-success {
    background: rgba(34, 197, 94, 0.2) !important;
    color: #86efac !important;
}
    </style>
</head>
<body>
    <?php if (session()->get('isLoggedIn')): ?>
        <?= $this->include('templates/navbar') ?>
    <?php endif; ?>
    
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    
    <script>
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Initialize theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<div class="spinner"></div> Processing...';
                    }
                });
            });
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>