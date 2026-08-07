<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container" style="padding: 40px 20px;">
    <!-- Page Header -->
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">My Bookings</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Manage your current and past reservations</p>
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

    <!-- Filter Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 32px; border-bottom: 1px solid var(--border);">
        <button class="filter-tab active" data-filter="all">All Bookings</button>
        <button class="filter-tab" data-filter="upcoming">Upcoming</button>
        <button class="filter-tab" data-filter="active">Active</button>
        <button class="filter-tab" data-filter="completed">Completed</button>
        <button class="filter-tab" data-filter="cancelled">Cancelled</button>
    </div>

    <!-- Reservations List -->
    <div id="reservationsContainer">
        <?php if (empty($reservations)): ?>
            <div class="card text-center" style="padding: 80px 40px;">
                <i class="fas fa-calendar-times" style="font-size: 80px; color: var(--text-secondary); margin-bottom: 24px;"></i>
                <h3 style="color: var(--text-primary); margin-bottom: 12px; font-size: 1.5rem;">No Reservations Yet</h3>
                <p style="color: var(--text-secondary); margin-bottom: 32px;">Start your journey by booking your first vehicle.</p>
                <a href="<?= base_url('/customer/dashboard') ?>" class="btn btn-primary">
                    <i class="fas fa-car"></i> Browse Cars
                </a>
            </div>
        <?php else: ?>
            <div class="grid" style="gap: 24px;">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="card reservation-card" data-status="<?= $reservation['status'] ?>">
                        <div style="display: flex; gap: 24px;">
                            <!-- Car Image -->
                            <div style="width: 120px; height: 100px; background: var(--background); border-radius: 8px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($reservation['image'])): ?>
                                    <img src="<?= base_url('/uploads/cars/' . $reservation['image']) ?>" 
                                         alt="<?= $reservation['brand'] . ' ' . $reservation['model'] ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                <?php else: ?>
                                    <i class="fas fa-car" style="font-size: 32px; color: var(--text-secondary);"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Reservation Details -->
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: between; align-items: flex-start; margin-bottom: 16px;">
                                    <div>
                                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                                            <?= $reservation['brand'] ?> <?= $reservation['model'] ?>
                                        </h3>
                                        <p style="color: var(--text-secondary); font-size: 14px;">
                                            Reservation #<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?>
                                        </p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="status-badge status-<?= $reservation['status'] ?>">
                                            <?= ucfirst($reservation['status']) ?>
                                        </span>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-top: 4px;">
                                            ₱<?= $reservation['total_cost'] ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rental Period -->
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                                    <div>
                                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 4px;">Pick-up</div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            <?= date('M j, Y', strtotime($reservation['start_date'])) ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 4px;">Return</div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            <?= date('M j, Y', strtotime($reservation['end_date'])) ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 4px;">Duration</div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            <?php
                                                $start = new DateTime($reservation['start_date']);
                                                $end = new DateTime($reservation['end_date']);
                                                $days = $start->diff($end)->days + 1;
                                                echo $days . ' day' . ($days > 1 ? 's' : '');
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <?php if (in_array($reservation['status'], ['pending', 'confirmed'])): ?>
                                        <a href="<?= base_url('/customer/cancelReservation/' . $reservation['id']) ?>" 
                                           class="btn btn-outline" 
                                           style="padding: 8px 16px; font-size: 14px;"
                                           onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($reservation['status'] === 'completed'): ?>
                                        <button class="btn btn-outline" style="padding: 8px 16px; font-size: 14px;">
                                            <i class="fas fa-file-invoice"></i> Invoice
                                        </button>
                                        <button class="btn btn-outline" style="padding: 8px 16px; font-size: 14px;">
                                            <i class="fas fa-star"></i> Rate
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-outline" style="padding: 8px 16px; font-size: 14px;">
                                        <i class="fas fa-question-circle"></i> Support
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const reservationCards = document.querySelectorAll('.reservation-card');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            // Filter reservations
            reservationCards.forEach(card => {
                const status = card.getAttribute('data-status');
                
                if (filter === 'all' || 
                    (filter === 'upcoming' && status === 'confirmed') ||
                    (filter === 'active' && status === 'ongoing') ||
                    (filter === 'completed' && status === 'completed') ||
                    (filter === 'cancelled' && status === 'cancelled')) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show no results message if needed
            const visibleCards = Array.from(reservationCards).filter(card => card.style.display !== 'none');
            const noResults = document.getElementById('noResults');
            
            if (visibleCards.length === 0 && reservationCards.length > 0) {
                if (!noResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.id = 'noResults';
                    noResultsDiv.className = 'card text-center';
                    noResultsDiv.style.padding = '60px 40px';
                    noResultsDiv.innerHTML = `
                        <i class="fas fa-search" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--text-primary); margin-bottom: 12px;">No Reservations Found</h3>
                        <p style="color: var(--text-secondary);">No reservations match your current filter.</p>
                    `;
                    document.getElementById('reservationsContainer').appendChild(noResultsDiv);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    });
});
</script>

<style>
.filter-tab {
    padding: 12px 24px;
    background: none;
    border: none;
    color: var(--text-secondary);
    font-weight: 500;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.filter-tab:hover {
    color: var(--primary);
}

.filter-tab.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
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

.reservation-card {
    transition: all 0.3s ease;
}

.reservation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

@media (max-width: 768px) {
    .reservation-card > div {
        flex-direction: column;
    }
    
    .reservation-card > div > div:first-child {
        width: 100%;
        height: 150px;
        margin-bottom: 16px;
    }
    
    .filter-tab {
        padding: 8px 16px;
        font-size: 14px;
    }
}
</style>
<?= $this->endSection() ?>