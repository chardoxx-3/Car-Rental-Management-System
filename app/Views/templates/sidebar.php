<?php if (session()->get('isLoggedIn') && session()->get('role') === 'admin'): ?>
<aside style="background: var(--surface); border-right: 1px solid var(--border); min-height: calc(100vh - 73px); width: 280px; position: fixed; left: 0; top: 73px; overflow-y: auto;">
    <div style="padding: 24px;">
        <nav style="display: flex; flex-direction: column; gap: 8px;">
            <div style="padding: 8px 12px; margin-bottom: 16px;">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Main Menu</h3>
            </div>
            
            <a href="<?= base_url('/admin/dashboard') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-tachometer-alt" style="width: 20px;"></i>
                Dashboard
            </a>
            
            <div style="padding: 8px 12px; margin: 16px 0 8px 0;">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Management</h3>
            </div>
            
            <a href="<?= base_url('/admin/manageCars') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-car" style="width: 20px;"></i>
                Car Management
            </a>
            
            <a href="<?= base_url('/admin/manageReservations') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-calendar-check" style="width: 20px;"></i>
                Reservations
            </a>
            
            <a href="<?= base_url('/admin/manageCustomers') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-users" style="width: 20px;"></i>
                Customers
            </a>
            
            <a href="<?= base_url('/admin/managePayments') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-credit-card" style="width: 20px;"></i>
                Payments
            </a>
            
            <div style="padding: 8px 12px; margin: 16px 0 8px 0;">
                <h3 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Reports</h3>
            </div>
            
            <a href="<?= base_url('/admin/reports') ?>" class="sidebar-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-secondary); text-decoration: none; transition: all 0.3s ease; font-weight: 500;">
                <i class="fas fa-chart-bar" style="width: 20px;"></i>
                Analytics & Reports
            </a>
        </nav>
    </div>
</aside>

<style>
    .sidebar-item:hover {
        background: var(--primary);
        color: white !important;
    }
    
    .sidebar-item.active {
        background: var(--primary);
        color: white !important;
    }
    
    /* Mobile responsive */
    @media (max-width: 1024px) {
        aside {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 99;
        }
        
        aside.mobile-open {
            transform: translateX(0);
        }
    }
</style>

<script>
    // Set active sidebar item based on current URL
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        
        sidebarItems.forEach(item => {
            if (item.href && currentPath.includes(item.getAttribute('href').replace('<?= base_url() ?>', ''))) {
                item.classList.add('active');
            }
        });
    });
</script>
<?php endif; ?>