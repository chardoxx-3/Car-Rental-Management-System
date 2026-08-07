<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Customer Management</h1>
            <p style="color: var(--text-secondary);">Manage and view all registered customers</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Stats Overview -->
    <div class="grid grid-4" style="margin-bottom: 32px;">
        <div class="card text-center">
            <div style="width: 60px; height: 60px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-users" style="color: var(--primary); font-size: 24px;"></i>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $totalCustomers ?></div>
            <div style="color: var(--text-secondary);">Total Customers</div>
        </div>

        <div class="card text-center">
            <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-calendar-check" style="color: var(--success); font-size: 24px;"></i>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $activeReservations ?></div>
            <div style="color: var(--text-secondary);">Active Reservations</div>
        </div>

        <div class="card text-center">
            <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-star" style="color: var(--warning); font-size: 24px;"></i>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $avgReservationsPerCustomer ?></div>
            <div style="color: var(--text-secondary);">Avg. Reservations</div>
        </div>

        <div class="card text-center">
            <div style="width: 60px; height: 60px; background: rgba(139, 92, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-user-plus" style="color: #8b5cf6; font-size: 24px;"></i>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $newCustomersThisMonth ?></div>
            <div style="color: var(--text-secondary);">New This Month</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <form method="get" action="<?= base_url('/admin/manageCustomers') ?>" id="filterForm">
            <div style="display: grid; grid-template-columns: 1fr auto auto; gap: 16px; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search Customers</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." 
                           value="<?= esc($currentFilters['search'] ?? '') ?>" id="searchInput">
                </div>
                <div class="form-group">
                    <label class="form-label">Sort By</label>
                    <select class="form-control" name="sort" id="sortSelect" onchange="document.getElementById('filterForm').submit()">
                        <option value="newest" <?= ($currentFilters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        <option value="oldest" <?= ($currentFilters['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                        <option value="name" <?= ($currentFilters['sort'] ?? '') === 'name' ? 'selected' : '' ?>>Name A-Z</option>
                        <option value="activity" <?= ($currentFilters['sort'] ?? '') === 'activity' ? 'selected' : '' ?>>Most Active</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Customer</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Contact</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Join Date</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Reservations</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="6" style="padding: 60px 16px; text-align: center;">
                                <i class="fas fa-users" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px; display: block;"></i>
                                <h3 style="color: var(--text-primary); margin-bottom: 8px;">No Customers Found</h3>
                                <p style="color: var(--text-secondary);">No customers match your search criteria.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                            <?php 
                            $customerStats = $userModel->getCustomerStats($customer['id']);
                            $isActive = $customerStats['total_reservations'] > 0;
                            ?>
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s ease;">
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <span style="color: white; font-weight: 600; font-size: 0.875rem;">
                                                <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-primary);"><?= esc($customer['name']) ?></div>
                                            <div style="color: var(--text-secondary); font-size: 0.875rem;">ID: <?= str_pad($customer['id'], 6, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="color: var(--text-primary); font-weight: 500;"><?= esc($customer['email']) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= esc($customer['phone']) ?></div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="color: var(--text-primary);"><?= date('M j, Y', strtotime($customer['created_at'])) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= date('g:i A', strtotime($customer['created_at'])) ?></div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="color: var(--text-primary); font-weight: 600;"><?= $customerStats['total_reservations'] ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;">₱<?= number_format($customerStats['total_spent'], 2) ?> spent</div>
                                </td>
                                <td style="padding: 16px;">
                                    <span class="status-badge <?= $isActive ? 'status-active' : 'status-inactive' ?>">
                                        <?= $isActive ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; gap: 8px;">
                                        <button class="btn btn-outline" style="padding: 8px 12px;" onclick="viewCustomer(<?= $customer['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($customers) && isset($pager)): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px 16px 0 16px; border-top: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Showing <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> 
                    to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                    of <?= $pager->getTotal() ?> customers
                </div>
                <div style="display: flex; gap: 8px;">
                    <!-- Previous Page -->
                    <?php if ($pager->getCurrentPage() > 1): ?>
                        <a href="<?= $pager->getPageURI($pager->getCurrentPage() - 1) ?>" class="btn btn-outline" style="padding: 8px 12px;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline" disabled style="padding: 8px 12px;">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $pager->getPageCount(); $i++): ?>
                        <?php if ($i == $pager->getCurrentPage()): ?>
                            <button class="btn btn-primary" style="padding: 8px 12px;"><?= $i ?></button>
                        <?php else: ?>
                            <a href="<?= $pager->getPageURI($i) ?>" class="btn btn-outline" style="padding: 8px 12px;"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Next Page -->
                    <?php if ($pager->getCurrentPage() < $pager->getPageCount()): ?>
                        <a href="<?= $pager->getPageURI($pager->getCurrentPage() + 1) ?>" class="btn btn-outline" style="padding: 8px 12px;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline" disabled style="padding: 8px 12px;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Customer Detail Modal -->
<div id="customerModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">Customer Details</h2>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer;">&times;</button>
        </div>
        <div id="customerDetails">
            <!-- Customer details will be loaded here via AJAX -->
        </div>
    </div>
</div>

<style>
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: var(--surface);
    border-radius: 12px;
    padding: 32px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: 1fr;
    }
    
    .card > div:first-child > div {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function viewCustomer(customerId) {
    // Show loading state
    document.getElementById('customerDetails').innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
            <p style="margin-top: 16px; color: var(--text-secondary);">Loading customer details...</p>
        </div>
    `;
    
    document.getElementById('customerModal').style.display = 'flex';
    
    // AJAX call to get customer details - Use absolute URL
    fetch(`/admin/getCustomerDetails/${customerId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const customer = data.customer;
                const stats = data.stats;
                const recentReservations = data.recentReservations;
                
                let reservationsHtml = '';
                if (recentReservations && recentReservations.length > 0) {
                    recentReservations.forEach(reservation => {
                        const startDate = new Date(reservation.start_date).toLocaleDateString();
                        const endDate = new Date(reservation.end_date).toLocaleDateString();
                        reservationsHtml += `
                            <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--background); border-radius: 8px;">
                                <i class="fas fa-car" style="color: var(--primary);"></i>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500;">${reservation.brand} ${reservation.model}</div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                        ${startDate} - ${endDate} • ${reservation.status}
                                    </div>
                                </div>
                                <div style="font-weight: 600; color: var(--primary);">₱${parseFloat(reservation.total_cost || 0).toFixed(2)}</div>
                            </div>
                        `;
                    });
                } else {
                    reservationsHtml = '<p style="color: var(--text-secondary); text-align: center; padding: 20px;">No recent reservations found</p>';
                }
                
                const joinDate = new Date(customer.created_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                const customerDetails = `
                    <div class="grid grid-2" style="gap: 24px; margin-bottom: 24px;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Personal Information</div>
                            <div style="background: var(--background); padding: 16px; border-radius: 8px;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: white; font-weight: 600; font-size: 1.25rem;">
                                            ${customer.name.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">${customer.name}</div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                            Customer since ${joinDate}
                                        </div>
                                    </div>
                                </div>
                                <div style="display: grid; gap: 12px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Email:</span>
                                        <span style="font-weight: 500;">${customer.email}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Phone:</span>
                                        <span style="font-weight: 500;">${customer.phone || 'N/A'}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Address:</span>
                                        <span style="font-weight: 500; text-align: right;">${customer.address || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Rental Statistics</div>
                            <div style="background: var(--background); padding: 16px; border-radius: 8px;">
                                <div style="display: grid; gap: 12px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Total Reservations:</span>
                                        <span style="font-weight: 600; color: var(--primary);">${stats.total_reservations || 0}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Completed:</span>
                                        <span style="font-weight: 600; color: var(--success);">${stats.completed_reservations || 0}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Total Spent:</span>
                                        <span style="font-weight: 600; color: var(--primary);">₱${parseFloat(stats.total_spent || 0).toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid var(--border); padding-top: 24px;">
                        <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Recent Reservations</div>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            ${reservationsHtml}
                        </div>
                    </div>
                `;
                
                document.getElementById('customerDetails').innerHTML = customerDetails;
            } else {
                document.getElementById('customerDetails').innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: var(--error); margin-bottom: 16px;"></i>
                        <h3 style="color: var(--text-primary); margin-bottom: 8px;">Error Loading Details</h3>
                        <p style="color: var(--text-secondary);">${data.message || 'Failed to load customer details'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('customerDetails').innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: var(--error); margin-bottom: 16px;"></i>
                    <h3 style="color: var(--text-primary); margin-bottom: 8px;">Network Error</h3>
                    <p style="color: var(--text-secondary);">Unable to load customer details. Please check your connection and try again.</p>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">Error: ${error.message}</p>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('customerModal').style.display = 'none';
}

function exportCustomers() {
    // Get current filter parameters
    const search = document.getElementById('searchInput').value;
    const sort = document.getElementById('sortSelect').value;
    
    // Redirect to export endpoint with current filters
    window.location.href = `/admin/exportCustomers?search=${encodeURIComponent(search)}&sort=${encodeURIComponent(sort)}`;
}

// Auto-submit form when search input changes (with debounce)
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('customerModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
<?= $this->endSection() ?>