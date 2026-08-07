<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>

<!-- Background Container -->
<div class="cinematic-background">
    <!-- Single Static Background Image -->
    <div class="bg-image" style="background-image: url('<?= base_url('images/lamborghini-si-n-3840x2160-24395.jpg') ?>')"></div>
    
    <!-- Dark Overlay to ensure text readability -->
    <div class="overlay"></div>
</div>

<!-- Main Content Wrapper -->
<div class="login-wrapper">
    <div class="glass-card">
        <!-- Brand Header -->
        <div class="card-header">
            <div class="brand-logo">
                <i class="fas fa-car"></i>
            </div>
            <h1>Welcome Back</h1>
            <p>Enter your credentials to access DriveRent</p>
        </div>

        <!-- Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?= base_url('/auth/processLogin') ?>" class="login-form">
            
            <div class="input-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" value="<?= old('email') ?>" required>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="card-footer">
            <div class="divider">
                <span>OR</span>
            </div>
            <p>
                Don't have an account? 
                <a href="<?= base_url('/auth/register') ?>" class="signup-link">Create Account</a>
            </p>
        </div>
    </div>
</div>

<style>
    /* --- Layout & Background --- */
    .cinematic-background {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        z-index: 0;
        overflow: hidden;
        background-color: #1a1a1a;
    }

    .bg-image {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        /* Animation properties removed */
    }


    /* --- Centering Wrapper --- */
    .login-wrapper {
        position: relative;
        z-index: 10;
        min-height: calc(100vh - 73px); /* Adjust based on navbar height */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    /* --- Glass Card Design --- */
    .glass-card {
        width: 100%;
        max-width: 420px;
        background: rgba(255, 255, 255, 0.75); /* Light Mode Glass */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transition: transform 0.3s ease;
    }

    /* Dark Mode Glass Support */
    [data-theme="dark"] .glass-card {
        background: rgba(30, 41, 59, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .card-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .brand-logo {
        width: 60px; height: 60px;
        background: var(--primary);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
        color: white; font-size: 24px;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        transform: rotate(-5deg);
    }

    .card-header h1 {
        font-size: 1.75rem; font-weight: 800; color: var(--text-primary);
        margin-bottom: 8px;
    }
    .card-header p { color: var(--text-secondary); font-size: 0.95rem; }

    /* --- Form Elements --- */
    .input-group { margin-bottom: 20px; }
    .input-group label {
        display: block; font-size: 0.9rem; font-weight: 600;
        color: var(--text-primary); margin-bottom: 8px;
    }

    .input-wrapper { position: relative; }
    
    .input-icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: var(--text-secondary); transition: color 0.3s;
    }

    .login-form input {
        width: 100%;
        padding: 12px 16px 12px 48px;
        border-radius: 12px;
        border: 2px solid transparent; /* Prepare for focus border */
        background: rgba(255, 255, 255, 0.6); /* Translucent input bg */
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    /* Input Focus State */
    .login-form input:focus {
        outline: none;
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .login-form input:focus + .input-icon, 
    .login-form input:focus ~ .input-icon { color: var(--primary); }

    [data-theme="dark"] .login-form input {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255,255,255,0.1);
    }
    [data-theme="dark"] .login-form input:focus {
        background: rgba(15, 23, 42, 0.9);
        border-color: var(--primary);
    }

    /* --- Form Actions --- */
    .form-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; font-size: 0.9rem;
    }

    .checkbox-container {
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        color: var(--text-secondary); user-select: none;
    }
    .forgot-link {
        color: var(--primary); text-decoration: none; font-weight: 600;
        transition: opacity 0.2s;
    }
    .forgot-link:hover { text-decoration: underline; }

    /* --- Button --- */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white; border: none; border-radius: 12px;
        font-size: 1rem; font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5);
    }
    .btn-submit:active { transform: translateY(0); }

    /* --- Footer --- */
    .card-footer { margin-top: 24px; text-align: center; }
    
    .divider {
        display: flex; align-items: center; margin-bottom: 20px;
    }
    .divider::before, .divider::after {
        content: ""; flex: 1; height: 1px; background: rgba(0,0,0,0.1);
    }
    [data-theme="dark"] .divider::before, 
    [data-theme="dark"] .divider::after { background: rgba(255,255,255,0.1); }
    
    .divider span {
        padding: 0 16px; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600;
    }

    .signup-link {
        color: var(--primary); font-weight: 700; text-decoration: none;
        margin-left: 4px;
    }
    .signup-link:hover { text-decoration: underline; }

    /* Alerts */
    .alert {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;
        font-size: 0.9rem; display: flex; align-items: center; gap: 10px;
    }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }

    @media (max-width: 480px) {
        .glass-card { padding: 24px; }
        .login-wrapper { padding: 16px; }
    }
</style>
<?= $this->endSection() ?>