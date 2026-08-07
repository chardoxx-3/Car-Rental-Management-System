<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                Reservation Details
            </h1>
            <p style="color: var(--text-secondary);">Complete information for reservation #<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="btn btn-outline" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <button class="btn btn-primary" onclick="printReservation()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Main Content -->
        <div>
            <!-- Reservation Overview -->
            <div class="card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 24px;">
                    <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary);">Reservation Overview</h2>
                    <span class="status-badge status-<?= $reservation['status'] ?>" style="font-size: 0.875rem;">
                        <?= ucfirst($reservation['status']) ?>
                    </span>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Reservation ID
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary); font-weight: 600;">
                            #<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Created Date
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary);">
                            <?= date('F j, Y g:i A', strtotime($reservation['created_at'])) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Total Amount
                        </h3>
                        <p style="font-size: 1.5rem; color: var(--primary); font-weight: 700;">
                            ₱<?= number_format($reservation['total_cost'], 2) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Rental Duration
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary);">
                            <?php
                                $start = new DateTime($reservation['start_date']);
                                $end = new DateTime($reservation['end_date']);
                                $days = $start->diff($end)->days + 1;
                                echo $days . ' day' . ($days > 1 ? 's' : '');
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card" style="margin-bottom: 24px;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">
                    Customer Information
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Full Name
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary); font-weight: 600;">
                            <?= esc($reservation['customer_name']) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Email Address
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary);">
                            <?= esc($reservation['email']) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Phone Number
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary);">
                            <?= esc($reservation['phone'] ?? 'Not provided') ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="card" style="margin-bottom: 24px;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">
                    Vehicle Information
                </h2>
                
                <div style="display: flex; gap: 24px; align-items: flex-start;">
                    <?php if (!empty($reservation['image'])): ?>
                    <div style="flex-shrink: 0;">
                        <img src="/uploads/cars/<?= $reservation['image'] ?>" 
                             alt="<?= esc($reservation['brand']) ?> <?= esc($reservation['model']) ?>" 
                             style="width: 200px; height: 120px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <?php endif; ?>
                    
                    <div style="flex-grow: 1;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Vehicle
                                </h3>
                                <p style="font-size: 1.25rem; color: var(--text-primary); font-weight: 600;">
                                    <?= esc($reservation['brand']) ?> <?= esc($reservation['model']) ?> (<?= $reservation['year'] ?>)
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Plate Number
                                </h3>
                                <p style="font-size: 1rem; color: var(--text-primary); font-weight: 600;">
                                    <?= esc($reservation['plate_number']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Color
                                </h3>
                                <p style="font-size: 1rem; color: var(--text-primary);">
                                    <?= esc($reservation['color']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Transmission
                                </h3>
                                <p style="font-size: 1rem; color: var(--text-primary); text-transform: capitalize;">
                                    <?= esc($reservation['transmission']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Capacity
                                </h3>
                                <p style="font-size: 1rem; color: var(--text-primary);">
                                    <?= $reservation['capacity'] ?> seats
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                    Daily Rate
                                </h3>
                                <p style="font-size: 1rem; color: var(--text-primary); font-weight: 600;">
                                    ₱<?= number_format($reservation['daily_rate'], 2) ?>
                                </p>
                            </div>
                        </div>
                        
                        <?php if (!empty($reservation['description'])): ?>
                        <div style="margin-top: 16px;">
                            <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                                Description
                            </h3>
                            <p style="font-size: 1rem; color: var(--text-primary); line-height: 1.5;">
                                <?= esc($reservation['description']) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Rental Period -->
            <div class="card">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">
                    Rental Period
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Pick-up Date
                        </h3>
                        <p style="font-size: 1.25rem; color: var(--text-primary); font-weight: 600;">
                            <?= date('F j, Y', strtotime($reservation['start_date'])) ?>
                        </p>
                        <p style="font-size: 1rem; color: var(--text-secondary);">
                            <?= date('l', strtotime($reservation['start_date'])) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                            Return Date
                        </h3>
                        <p style="font-size: 1.25rem; color: var(--text-primary); font-weight: 600;">
                            <?= date('F j, Y', strtotime($reservation['end_date'])) ?>
                        </p>
                        <p style="font-size: 1rem; color: var(--text-secondary);">
                            <?= date('l', strtotime($reservation['end_date'])) ?>
                        </p>
                    </div>
                </div>
                
                <div style="margin-top: 24px; padding: 16px; background: var(--background); border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: var(--text-primary);">Total Rental Days:</span>
                        <span style="font-weight: 700; color: var(--primary);">
                            <?= $days ?> day<?= $days > 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>

            <!-- Payment Information -->
            <div class="card">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    Payment Information
                </h2>
                
                <?php if (!empty($payment)): ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                            Amount Paid
                        </h3>
                        <p style="font-size: 1.5rem; color: var(--success); font-weight: 700;">
                            ₱<?= number_format($payment['amount'], 2) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                            Payment Method
                        </h3>
                        <p style="font-size: 1rem; color: var(--text-primary); text-transform: capitalize;">
                            <?= str_replace('_', ' ', $payment['payment_method']) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                            Payment Status
                        </h3>
                        <span class="status-badge status-<?= $payment['status'] ?>" style="font-size: 0.75rem;">
                            <?= ucfirst($payment['status']) ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($payment['transaction_id'])): ?>
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                            Transaction ID
                        </h3>
                        <p style="font-size: 0.875rem; color: var(--text-primary); font-family: monospace;">
                            <?= $payment['transaction_id'] ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                            Payment Date
                        </h3>
                        <p style="font-size: 0.875rem; color: var(--text-primary);">
                            <?= date('F j, Y g:i A', strtotime($payment['payment_date'])) ?>
                        </p>
                    </div>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-credit-card" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px;"></i>
                    <p style="color: var(--text-secondary);">No payment information available</p>
                </div>
                <?php endif; ?>
            </div>
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
    display: inline-block;
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

.btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    background: var(--success-dark);
}

.btn-danger {
    background: var(--error);
    color: white;
}

.btn-danger:hover {
    background: var(--error-dark);
}

.btn-secondary {
    background: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    background: var(--secondary-dark);
}

.btn-outline {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--border);
}

.btn-outline:hover {
    background: var(--background);
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
}

@media print {
    .btn, .action-menu {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

<script>
function updateStatus(status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    if (confirm(`Are you sure you want to mark this reservation as ${statusText}?`)) {
        fetch('<?= site_url("reservations/updateStatus/") ?><?= $reservation['id'] ?>', {
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
    }
}

function printReservation() {
    window.print();
}

// Add keyboard shortcut for printing
document.addEventListener('keydown', function(event) {
    if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
        event.preventDefault();
        printReservation();
    }
});
</script>

<?= $this->endSection() ?>