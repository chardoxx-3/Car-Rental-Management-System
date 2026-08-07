<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Reservation Management</h1>
            <p style="color: var(--text-secondary);">Manage all bookings and rental requests</p>
        </div>
    </div>

    <!-- Status Overview -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 32px;">
        <div class="card text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $statusCounts['total'] ?></div>
            <div style="color: var(--text-secondary);">Total</div>
        </div>
        <div class="card text-center" style="border-left: 4px solid var(--warning);">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 4px;"><?= $statusCounts['pending'] ?></div>
            <div style="color: var(--text-secondary);">Pending</div>
        </div>
        <div class="card text-center" style="border-left: 4px solid var(--success);">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 4px;"><?= $statusCounts['confirmed'] ?></div>
            <div style="color: var(--text-secondary);">Confirmed</div>
        </div>
        <div class="card text-center" style="border-left: 4px solid var(--primary);">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 4px;"><?= $statusCounts['ongoing'] ?></div>
            <div style="color: var(--text-secondary);">Active</div>
        </div>
        <div class="card text-center" style="border-left: 4px solid var(--secondary);">
            <div style="font-size: 2rem; font-weight: 700; color: var(--secondary); margin-bottom: 4px;"><?= $statusCounts['completed'] ?></div>
            <div style="color: var(--text-secondary);">Completed</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <form id="filterForm" method="GET" action="<?= site_url('admin/manageReservations') ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto auto; gap: 16px; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" placeholder="Customer, car, or reservation ID..." 
                           name="search" id="searchInput" value="<?= esc($currentFilters['search'] ?? '') ?>"
                           oninput="debounceSearch()">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status" id="statusFilter" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" <?= ($currentFilters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= ($currentFilters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="ongoing" <?= ($currentFilters['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="completed" <?= ($currentFilters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($currentFilters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date Range</label>
                    <select class="form-control" name="date_filter" id="dateFilter" onchange="this.form.submit()">
                        <option value="">All Dates</option>
                        <option value="today" <?= ($currentFilters['date_filter'] ?? '') === 'today' ? 'selected' : '' ?>>Today</option>
                        <option value="week" <?= ($currentFilters['date_filter'] ?? '') === 'week' ? 'selected' : '' ?>>This Week</option>
                        <option value="month" <?= ($currentFilters['date_filter'] ?? '') === 'month' ? 'selected' : '' ?>>This Month</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-outline" style="white-space: nowrap;" onclick="clearFilters()">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="white-space: nowrap; display: none;" id="applyFilters">
                        <i class="fas fa-search"></i> Apply
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Reservation ID</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Customer</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Vehicle</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Rental Period</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Amount</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="7" style="padding: 60px 16px; text-align: center;">
                                <i class="fas fa-calendar-times" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px; display: block;"></i>
                                <h3 style="color: var(--text-primary); margin-bottom: 8px;">No Reservations Found</h3>
                                <p style="color: var(--text-secondary);">No reservations match your search criteria.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s ease;">
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: var(--text-primary);">#<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                        <?= date('M j, Y', strtotime($reservation['created_at'])) ?>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= esc($reservation['customer_name']) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= esc($reservation['email']) ?></div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= esc($reservation['brand']) ?> <?= esc($reservation['model']) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= esc($reservation['plate_number']) ?></div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 500; color: var(--text-primary);">
                                        <?= date('M j', strtotime($reservation['start_date'])) ?> - <?= date('M j, Y', strtotime($reservation['end_date'])) ?>
                                    </div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                        <?php
                                            $start = new DateTime($reservation['start_date']);
                                            $end = new DateTime($reservation['end_date']);
                                            $days = $start->diff($end)->days + 1;
                                            echo $days . ' day' . ($days > 1 ? 's' : '');
                                        ?>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 700; color: var(--primary);">₱<?= number_format($reservation['total_cost'], 2) ?></div>
                                </td>
                                <td style="padding: 16px;">
                                    <span class="status-badge status-<?= $reservation['status'] ?>">
                                        <?= ucfirst($reservation['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; gap: 8px;">
<button class="btn btn-outline" style="padding: 8px 12px;" onclick="viewReservation(<?= $reservation['id'] ?>)">
    <i class="fas fa-eye"></i>
</button>
                                        <div style="position: relative;">
                                            <button class="btn btn-outline" style="padding: 8px 12px;" onclick="toggleActions(<?= $reservation['id'] ?>)">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div id="actions-<?= $reservation['id'] ?>" class="action-menu" style="display: none;">
                                                <button onclick="updateStatus(<?= $reservation['id'] ?>, 'confirmed')" class="action-item">
                                                    <i class="fas fa-check"></i> Confirm
                                                </button>
                                                <button onclick="updateStatus(<?= $reservation['id'] ?>, 'ongoing')" class="action-item">
                                                    <i class="fas fa-play"></i> Start Rental
                                                </button>
                                                <button onclick="updateStatus(<?= $reservation['id'] ?>, 'completed')" class="action-item">
                                                    <i class="fas fa-flag-checkered"></i> Complete
                                                </button>
                                                <button onclick="updateStatus(<?= $reservation['id'] ?>, 'cancelled')" class="action-item" style="color: var(--error);">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Real Pagination -->
        <?php if (!empty($reservations) && $pager): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px 16px 0 16px; border-top: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Showing <?= count($reservations) ?> of <?= $pager->getTotal() ?> reservations
                </div>
                <div style="display: flex; gap: 8px;">
                    <?= $pager->links() ?>
                </div>
            </div>
        <?php endif; ?>
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

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-confirmed {
    background: #d1fae5;
    color: #065f46;
}

.status-ongoing {
    background: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background: #e5e7eb;
    color: #374151;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.action-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    z-index: 10;
    min-width: 160px;
}

.action-item {
    width: 100%;
    padding: 12px 16px;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    color: var(--text-primary);
    font-size: 0.875rem;
    transition: background 0.3s ease;
}

.action-item:hover {
    background: var(--background);
}

.pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.pagination li {
    display: inline-block;
}

.pagination a, .pagination span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: var(--background);
}

.pagination .active span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid-5 {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .card > div:first-child > div {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .grid-5 {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
let searchTimeout;

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('filterForm').submit();
}

function toggleActions(reservationId) {
    const menu = document.getElementById('actions-' + reservationId);
    const allMenus = document.querySelectorAll('.action-menu');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== 'actions-' + reservationId) {
            m.style.display = 'none';
        }
    });
    
    // Toggle current menu
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function updateStatus(reservationId, status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    if (confirm(`Are you sure you want to mark this reservation as ${statusText}?`)) {
        fetch('<?= site_url("reservations/updateStatus/") ?>' + reservationId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'status=' + status
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
        
        document.getElementById('actions-' + reservationId).style.display = 'none';
    }
}

function viewReservation(reservationId) {
    window.location.href = '<?= site_url("admin/viewReservation/") ?>' + reservationId;
}

function exportReservations() {
    // Get current filter parameters
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    // Build export URL with current filters
    let exportUrl = '<?= site_url("admin/exportReservations") ?>?';
    const params = new URLSearchParams();
    
    if (search) params.append('search', search);
    if (status) params.append('status', status);
    if (dateFilter) params.append('date_filter', dateFilter);
    
    exportUrl += params.toString();
    window.location.href = exportUrl;
}

// Close action menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-menu') && !event.target.closest('.btn-outline')) {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});
</script>
<?= $this->endSection() ?>