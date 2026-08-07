<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container" style="max-width: 800px; margin: 40px auto;">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <h1 style="margin-bottom: 30px; color: var(--text-primary);">My Profile</h1>
    
    <div class="grid grid-2" style="gap: 30px;">
        <!-- Profile Information Form -->
        <div class="card">
            <h2 style="margin-bottom: 20px; font-size: 18px; color: var(--text-primary);">Personal Information</h2>
            <form action="<?= base_url('/customer/updateProfile') ?>" method="post">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" required><?= esc($user['address']) ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
        
        <!-- Change Password Form -->
        <div class="card">
            <h2 style="margin-bottom: 20px; font-size: 18px; color: var(--text-primary);">Change Password</h2>
            <form action="<?= base_url('/customer/changePassword') ?>" method="post">
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
            
            <!-- Account Information -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border);">
                <h3 style="margin-bottom: 15px; font-size: 16px; color: var(--text-primary);">Account Information</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <div>
                        <span style="color: var(--text-secondary); font-size: 14px;">Member Since:</span>
                        <div style="color: var(--text-primary);"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 14px;">Account Type:</span>
                        <div style="color: var(--text-primary); text-transform: capitalize;"><?= $user['role'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>