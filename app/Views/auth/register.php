<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>

<!-- Background Container (Same as Login) -->
<div class="cinematic-background">
    <!-- Single Static Background Image -->
    <div class="bg-image" style="background-image: url('<?= base_url('images/lamborghini-si-n-3840x2160-24395.jpg') ?>')"></div>
    <!-- Dark Overlay -->
    <div class="overlay"></div>
</div>

<!-- Main Content Wrapper -->
<div class="auth-wrapper">
    <div class="glass-card">
        <!-- Brand Header -->
        <div class="card-header">
            <div class="brand-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Join DriveRent and start your journey</p>
        </div>

        <!-- Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?= base_url('/auth/processRegister') ?>" class="auth-form">
            
            <!-- Row 1: Name & Phone -->
            <div class="form-grid">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="name" name="name" placeholder="John Doe" value="<?= old('name') ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="phone">Phone</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" id="phone" name="phone" placeholder="0912..." value="<?= old('phone') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" placeholder="email@example.com" value="<?= old('email') ?>" required>
                </div>
            </div>

            <!-- Address -->
            <div class="input-group">
                <label for="address">Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-map-marker-alt input-icon icon-top"></i>
                    <textarea id="address" name="address" placeholder="Enter your full address" rows="2" required><?= old('address') ?></textarea>
                </div>
            </div>

            <!-- Row 2: Passwords -->
            <div class="form-grid">
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="Min 6 chars" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="confirm_password">Confirm</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms-group">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="terms" required>
                    <span class="checkmark"></span>
                    <span class="label-text">
                        I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <span>Create Account</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="card-footer">
            <div class="divider">
                <span>OR</span>
            </div>
            <p>
                Already have an account? 
                <a href="<?= base_url('/auth/login') ?>" class="signup-link">Sign In</a>
            </p>
        </div>
    </div>
</div>

<style>
    /* --- Layout & Background (Identical to Login) --- */
    .cinematic-background {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        z-index: 0; overflow: hidden; background-color: #1a1a1a;
    }
    .bg-image {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background-size: cover; background-position: center; background-repeat: no-repeat;
    }


    /* --- Wrapper --- */
    .auth-wrapper {
        position: relative; z-index: 10;
        min-height: calc(100vh - 73px);
        display: flex; align-items: center; justify-content: center;
        padding: 40px 20px;
    }

    /* --- Glass Card --- */
    .glass-card {
        width: 100%;
        max-width: 520px; /* Slightly wider than login for the 2-column grid */
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    [data-theme="dark"] .glass-card {
        background: rgba(30, 41, 59, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    /* --- Header --- */
    .card-header { text-align: center; margin-bottom: 30px; }
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
    .card-header h1 { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
    .card-header p { color: var(--text-secondary); font-size: 0.95rem; }

    /* --- Form Grid Layout --- */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* --- Inputs --- */
    .input-group { margin-bottom: 16px; }
    .input-group label {
        display: block; font-size: 0.85rem; font-weight: 600;
        color: var(--text-primary); margin-bottom: 6px;
    }
    .input-wrapper { position: relative; }
    
    .input-icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: var(--text-secondary); transition: color 0.3s;
        pointer-events: none;
    }
    /* Special handling for Textarea Icon */
    .input-icon.icon-top { top: 16px; transform: none; }

    .auth-form input, .auth-form textarea {
        width: 100%;
        padding: 12px 16px 12px 48px;
        border-radius: 12px;
        border: 2px solid transparent;
        background: rgba(255, 255, 255, 0.6);
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        font-family: inherit;
    }
    .auth-form textarea { resize: vertical; min-height: 80px; }

    .auth-form input:focus, .auth-form textarea:focus {
        outline: none; background: #fff; border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .auth-form input:focus + .input-icon,
    .auth-form textarea:focus + .input-icon { color: var(--primary); }

    [data-theme="dark"] .auth-form input, 
    [data-theme="dark"] .auth-form textarea {
        background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.1);
    }
    [data-theme="dark"] .auth-form input:focus,
    [data-theme="dark"] .auth-form textarea:focus {
        background: rgba(15, 23, 42, 0.9); border-color: var(--primary);
    }

    /* --- Terms Checkbox --- */
    .terms-group { margin: 20px 0 24px; }
    .checkbox-wrapper {
        display: flex; align-items: center; cursor: pointer; user-select: none; gap: 10px;
    }
    .checkbox-wrapper input {
        width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;
    }
    .label-text { font-size: 0.9rem; color: var(--text-secondary); }
    .label-text a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .label-text a:hover { text-decoration: underline; }

    /* --- Button --- */
    .btn-submit {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white; border: none; border-radius: 12px;
        font-size: 1rem; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.5); }
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
    .divider span { padding: 0 16px; color: var(--text-secondary); font-size: 0.8rem; font-weight: 600; }
    
    .signup-link { color: var(--primary); font-weight: 700; text-decoration: none; margin-left: 4px; }
    .signup-link:hover { text-decoration: underline; }

    /* --- Alerts --- */
    .alert {
        padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
        font-size: 0.9rem; display: flex; align-items: center; gap: 10px;
    }
    .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }

    /* Mobile Responsive */
    @media (max-width: 540px) {
        .glass-card { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .auth-wrapper { padding: 15px; }
    }
</style>

<script>
    // Simple Client-side validation for visual feedback
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirm = this.value;
        const input = this;
        
        if (confirm.length > 0) {
            if (password !== confirm) {
                input.style.borderColor = '#ef4444';
                input.style.backgroundColor = 'rgba(239, 68, 68, 0.05)';
            } else {
                input.style.borderColor = '#22c55e';
                input.style.backgroundColor = 'rgba(34, 197, 94, 0.05)';
            }
        } else {
            input.style.borderColor = 'transparent';
            input.style.backgroundColor = '';
        }
    });
</script>
<?= $this->endSection() ?>