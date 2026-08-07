<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Car Management</h1>
        <p style="color: var(--text-secondary);">Manage your vehicle fleet and availability</p>
    </div>
    <a href="<?= base_url('/admin/addCar') ?>" class="btn btn-primary" style="display: flex; align-items: center; gap: 8px; white-space: nowrap;">
        <i class="fas fa-plus"></i> Add New Car
    </a>
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

    <!-- Filters and Search -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div class="form-group">
                <label class="form-label">Search Cars</label>
                <input type="text" class="form-control" placeholder="Search by brand, model, or plate..." id="searchInput">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-control" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Transmission</label>
                <select class="form-control" id="transmissionFilter">
                    <option value="">All Types</option>
                    <option value="automatic">Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Cars Table -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Car</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Details</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Price</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cars)): ?>
                        <tr>
                            <td colspan="5" style="padding: 60px 16px; text-align: center;">
                                <i class="fas fa-car" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px; display: block;"></i>
                                <h3 style="color: var(--text-primary); margin-bottom: 8px;">No Cars Found</h3>
                                <p style="color: var(--text-secondary); margin-bottom: 24px;">Get started by adding your first vehicle to the fleet.</p>
                                <a href="<?= base_url('/admin/addCar') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Car
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cars as $car): ?>
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s ease;">
                                <td style="padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 60px; height: 60px; background: var(--background); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <?php if (!empty($car['image'])): ?>
                                                <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" 
                                                     alt="<?= $car['brand'] . ' ' . $car['model'] ?>" 
                                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                            <?php else: ?>
                                                <i class="fas fa-car" style="color: var(--text-secondary);"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['brand'] ?> <?= $car['model'] ?></div>
                                            <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= $car['plate_number'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                        <div><?= $car['year'] ?> • <?= $car['color'] ?></div>
                                        <div><?= $car['capacity'] ?> seats • <?= ucfirst($car['transmission']) ?></div>
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: var(--primary);">₱<?= $car['daily_rate'] ?>/day</div>
                                </td>
                                <td style="padding: 16px;">
                                    <span class="status-badge status-<?= $car['status'] ?>">
                                        <?= ucfirst($car['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="<?= base_url('/admin/editCar/' . $car['id']) ?>" class="btn btn-outline" style="padding: 8px 12px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('/admin/deleteCar/' . $car['id']) ?>" 
                                           class="btn btn-outline" 
                                           style="padding: 8px 12px; color: var(--error); border-color: var(--error);"
                                           onclick="return confirm('Are you sure you want to delete this car? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

<!-- Pagination -->
<?php if (!empty($cars) && isset($pager)): ?>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px 16px 0 16px; border-top: 1px solid var(--border);">
        <div style="color: var(--text-secondary); font-size: 0.875rem;">
            Showing <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> 
            to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
            of <?= $pager->getTotal() ?> cars
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

<style>
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-available {
    background: #d1fae5;
    color: #065f46;
}

.status-unavailable {
    background: #fee2e2;
    color: #991b1b;
}

.status-maintenance {
    background: #fef3c7;
    color: #92400e;
}

table tbody tr:hover {
    background: var(--background);
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .card > div:first-child > div {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .container > div {
        padding: 20px !important;
    }
    
    table {
        display: block;
    }
    
    table thead {
        display: none;
    }
    
    table tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
    }
    
    table tbody td {
        display: block;
        padding: 8px 0 !important;
        border: none;
    }
    
    table tbody td:before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-primary);
        display: block;
        margin-bottom: 4px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const transmissionFilter = document.getElementById('transmissionFilter');
    const tableRows = document.querySelectorAll('tbody tr');
    const pagination = document.querySelector('.pagination-container'); // Add class to pagination div
    
    function filterCars() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const transmissionValue = transmissionFilter.value;
        
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            if (row.cells.length === 1) return; // Skip no results row
            
            const carText = row.cells[0].textContent.toLowerCase();
            const statusBadge = row.cells[3].querySelector('.status-badge');
            const status = statusBadge ? statusBadge.className.includes(statusValue) : false;
            const transmission = row.cells[1].textContent.toLowerCase().includes(transmissionValue);
            
            const matchesSearch = carText.includes(searchTerm);
            const matchesStatus = !statusValue || status;
            const matchesTransmission = !transmissionValue || transmission;
            
            if (matchesSearch && matchesStatus && matchesTransmission) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Hide pagination when filtering
        if (pagination) {
            if (searchTerm || statusValue || transmissionValue) {
                pagination.style.display = 'none';
            } else {
                pagination.style.display = 'flex';
            }
        }
    }
    
    searchInput.addEventListener('input', filterCars);
    statusFilter.addEventListener('change', filterCars);
    transmissionFilter.addEventListener('change', filterCars);
});
</script>
<?= $this->endSection() ?>