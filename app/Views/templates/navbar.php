<?php 
    // Helper variable to check role for cleaner HTML
    $isLoggedIn = session()->get('isLoggedIn');
    $isAdmin = $isLoggedIn && session()->get('role') === 'admin';
?>

<nav style="background: var(--surface); border-bottom: 1px solid var(--border); padding: 16px 0; position: sticky; top: 0; z-index: 100; backdrop-filter: blur(10px);">
    <!-- 
        LAYOUT LOGIC:
        If Admin: Use 'padding: 0 24px' (Full Width / Fluid)
        If User:  Use 'class="container"' (Centered / Fixed Width)
    -->
    <div class="<?= $isAdmin ? '' : 'container' ?>" style="<?= $isAdmin ? 'padding: 0 24px; width: 100%;' : '' ?>">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            
            <!-- LEFT SIDE: Logo & Title -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-car" style="color: white; font-size: 16px;"></i>
                </div>
                <span style="font-size: 20px; font-weight: 700; color: var(--text-primary);">
                    <?= $isAdmin ? 'DriveRent Admin' : 'DriveRent' ?>
                </span>
            </div>

            <!-- RIGHT SIDE: Links, Menu, Theme -->
            <!-- margin-left: auto pushes this block to the far right corner -->
            <div style="display: flex; align-items: center; gap: 32px; margin-left: auto;">
                
                <?php if ($isLoggedIn): ?>
                    
                    <!-- Navigation Links (Only visible to Customers) -->
                    <?php if (!$isAdmin): ?>
                        <div class="nav-links" style="display: flex; gap: 32px;">
                            <a href="<?= base_url('/customer/dashboard') ?>" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="fas fa-home"></i> Home
                            </a>
                            <a href="<?= base_url('/customer/myReservations') ?>" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="fas fa-calendar-alt"></i> My Bookings
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- User Menu & Theme (Common for both, but positioned differently due to parent container) -->
                    <div style="display: flex; align-items: center; gap: 16px;">
                        
                        <!-- User Dropdown -->
                        <div style="position: relative;">
                            <button onclick="toggleUserMenu()" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; transition: background 0.3s ease;">
                                <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="color: white; font-size: 14px;"></i>
                                </div>
                                <!-- Hide name on mobile/admin if desired, or keep it -->
                                <span style="font-weight: 500; color: var(--text-primary);"><?= session()->get('name') ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-secondary);"></i>
                            </button>
                            
                            <div id="userMenu" style="position: absolute; top: 100%; right: 0; margin-top: 8px; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; box-shadow: var(--shadow-lg); padding: 8px; min-width: 200px; display: none; z-index: 1000;">
                                <div style="padding: 12px; border-bottom: 1px solid var(--border);">
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= session()->get('name') ?></div>
                                    <div style="font-size: 14px; color: var(--text-secondary);"><?= session()->get('email') ?></div>
                                </div>
                                <a href="<?= $isAdmin ? base_url('/admin/dashboard') : base_url('/customer/dashboard') ?>" style="display: flex; align-items: center; gap: 8px; padding: 12px; color: var(--text-primary); text-decoration: none; border-radius: 6px; transition: background 0.3s ease;" onmouseover="this.style.background='var(--background)'">
    <i class="fas fa-tachometer-alt"></i>
    Dashboard
</a>
<a href="<?= $isAdmin ? base_url('/admin/profile') : base_url('/customer/profile') ?>" style="display: flex; align-items: center; gap: 8px; padding: 12px; color: var(--text-primary); text-decoration: none; border-radius: 6px; transition: background 0.3s ease;" onmouseover="this.style.background='var(--background)'">
    <i class="fas fa-user-edit"></i>
    Profile
</a>
                                <a href="<?= base_url('/auth/logout') ?>" style="display: flex; align-items: center; gap: 8px; padding: 12px; color: var(--error); text-decoration: none; border-radius: 6px; transition: background 0.3s ease;" onmouseover="this.style.background='var(--background)'">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </div>

                        <!-- Theme Toggle -->
                        <button onclick="toggleTheme()" style="background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.3s ease;" onmouseover="this.style.background='var(--background)'">
                            <i class="fas fa-moon" style="color: var(--text-secondary);"></i>
                        </button>
                    </div>

                <?php else: ?>
                    <!-- Guest Links -->
                    <div style="display: flex; align-items: center;">
                        <a href="<?= base_url('/auth/login') ?>" class="btn btn-outline" style="margin-right: 12px;">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="<?= base_url('/auth/register') ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                        <!-- Theme Toggle for Guests -->
                        <button onclick="toggleTheme()" style="background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.3s ease; margin-left: 8px;" onmouseover="this.style.background='var(--background)'">
                            <i class="fas fa-moon" style="color: var(--text-secondary);"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    // Close user menu when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.getElementById('userMenu');
        const userButton = event.target.closest('button');
        
        // Ensure we check if userMenu exists before checking style to prevent errors
        if (userMenu && !userButton && userMenu.style.display === 'block') {
            userMenu.style.display = 'none';
        }
    });
</script>

<style>
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

    /* Helper for User layout */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    @media (max-width: 768px) {
        /* Mobile adjustment to stack items */
        .nav-links {
            display: none !important; /* Hide links on mobile for simplicity, typically moving to a burger menu */
        }
        
        /* Ensure Admin fluid padding isn't too large on mobile */
        nav > div {
            padding: 0 16px !important; 
        }
    }
</style>