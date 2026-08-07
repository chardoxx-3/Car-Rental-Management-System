<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Payment Management</h1>
            <p style="color: var(--text-secondary);">Track and manage all payment transactions</p>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-4" style="margin-bottom: 32px;">
        <div class="card" style="border-left: 4px solid var(--success);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Total Revenue</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($totalRevenue, 2) ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-dollar-sign" style="color: var(--success); font-size: 20px;"></i>
                </div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--primary);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">This Month</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($monthlyRevenue, 2) ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar" style="color: var(--primary); font-size: 20px;"></i>
                </div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--warning);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Pending</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($pendingAmount, 2) ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: var(--warning); font-size: 20px;"></i>
                </div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--error);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Failed</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($failedAmount, 2) ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times-circle" style="color: var(--error); font-size: 20px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution -->
    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 32px; margin-bottom: 32px;">
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Payment Methods</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                <?php foreach ($paymentMethods as $method): ?>
                    <div style="text-align: center; padding: 20px; background: var(--background); border-radius: 8px;">
                        <?php if ($method['payment_method'] === 'credit_card'): ?>
                            <i class="fab fa-cc-visa" style="font-size: 32px; color: #1a1f71; margin-bottom: 8px;"></i>
                            <div style="font-weight: 600; color: var(--text-primary);">Credit Card</div>
                        <?php elseif ($method['payment_method'] === 'debit_card'): ?>
                            <i class="fas fa-credit-card" style="font-size: 32px; color: #1a1f71; margin-bottom: 8px;"></i>
                            <div style="font-weight: 600; color: var(--text-primary);">Debit Card</div>
                        <?php elseif ($method['payment_method'] === 'paypal'): ?>
                            <i class="fab fa-paypal" style="font-size: 32px; color: #0070ba; margin-bottom: 8px;"></i>
                            <div style="font-weight: 600; color: var(--text-primary);">PayPal</div>
                        <?php elseif ($method['payment_method'] === 'cash'): ?>
                            <i class="fas fa-money-bill-wave" style="font-size: 32px; color: var(--success); margin-bottom: 8px;"></i>
                            <div style="font-weight: 600; color: var(--text-primary);">Cash</div>
                        <?php else: ?>
                            <i class="fas fa-mobile-alt" style="font-size: 32px; color: var(--primary); margin-bottom: 8px;"></i>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= ucfirst($method['payment_method']) ?></div>
                        <?php endif; ?>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">
                            ₱<?= number_format($method['total_amount'], 2) ?>
                        </div>
                        <div style="color: var(--text-secondary); font-size: 0.75rem;">
                            <?= $method['count'] ?> transaction<?= $method['count'] > 1 ? 's' : '' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Quick Stats</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary);">Avg. Transaction</span>
                    <span style="font-weight: 600; color: var(--text-primary);">₱<?= number_format($avgTransaction, 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary);">Success Rate</span>
                    <span style="font-weight: 600; color: var(--success);"><?= $successRate ?>%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary);">Total Transactions</span>
                    <span style="font-weight: 600; color: var(--text-primary);"><?= $totalTransactions ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" placeholder="Transaction ID, customer, or amount..." id="searchInput">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-control" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select class="form-control" id="methodFilter">
                    <option value="">All Methods</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Transaction ID</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Customer</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Reservation</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Amount</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Method</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Date</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: var(--text-primary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="8" style="padding: 60px 16px; text-align: center;">
                                <i class="fas fa-credit-card" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px; display: block;"></i>
                                <h3 style="color: var(--text-primary); margin-bottom: 8px;">No Payments Found</h3>
                                <p style="color: var(--text-secondary);">No payment transactions have been recorded yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s ease;" class="payment-row">
                                <td style="padding: 16px;" data-label="Transaction ID">
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= $payment['transaction_id'] ?: 'N/A' ?></div>
                                </td>
                                <td style="padding: 16px;" data-label="Customer">
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= $payment['customer_name'] ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= $payment['email'] ?></div>
                                </td>
                                <td style="padding: 16px;" data-label="Reservation">
                                    <div style="font-weight: 600; color: var(--text-primary);">#<?= str_pad($payment['reservation_id'], 6, '0', STR_PAD_LEFT) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= $payment['brand'] ?> <?= $payment['model'] ?></div>
                                </td>
                                <td style="padding: 16px;" data-label="Amount">
                                    <div style="font-weight: 700; color: var(--primary);">₱<?= number_format($payment['amount'], 2) ?></div>
                                </td>
                                <td style="padding: 16px;" data-label="Method">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <?php if ($payment['payment_method'] === 'credit_card'): ?>
                                            <i class="fab fa-cc-visa" style="color: #1a1f71;"></i>
                                            <span>Credit Card</span>
                                        <?php elseif ($payment['payment_method'] === 'debit_card'): ?>
                                            <i class="fas fa-credit-card" style="color: #1a1f71;"></i>
                                            <span>Debit Card</span>
                                        <?php elseif ($payment['payment_method'] === 'paypal'): ?>
                                            <i class="fab fa-paypal" style="color: #0070ba;"></i>
                                            <span>PayPal</span>
                                        <?php elseif ($payment['payment_method'] === 'cash'): ?>
                                            <i class="fas fa-money-bill-wave" style="color: var(--success);"></i>
                                            <span>Cash</span>
                                        <?php else: ?>
                                            <i class="fas fa-credit-card" style="color: var(--text-secondary);"></i>
                                            <span><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="padding: 16px;" data-label="Date">
                                    <div style="color: var(--text-primary);"><?= date('M j, Y', strtotime($payment['payment_date'])) ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= date('g:i A', strtotime($payment['payment_date'])) ?></div>
                                </td>
                                <td style="padding: 16px;" data-label="Status">
                                    <span class="status-badge status-<?= $payment['status'] ?>">
                                        <?= ucfirst($payment['status']) ?>
                                    </span>
                                </td>
                                <td style="padding: 16px;" data-label="Actions">
                                    <div style="display: flex; gap: 8px;">
                                        <button class="btn btn-outline" style="padding: 8px 12px;" onclick="viewPayment('<?= $payment['id'] ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($payment['status'] === 'completed'): ?>
                                        <?php endif; ?>
                                        <button class="btn btn-outline" style="padding: 8px 12px;" onclick="printReceipt('<?= $payment['id'] ?>')">
                                            <i class="fas fa-print"></i>
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
        <?php if (!empty($payments) && isset($pager)): ?>
            <div class="pagination-container" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 16px 0 16px; border-top: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    Showing <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> 
                    to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                    of <?= $pager->getTotal() ?> payments
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

    <!-- Payment Details Modal -->
<div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 12px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px; border-bottom: 1px solid var(--border);">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary); margin: 0;">Payment Details</h2>
            <button onclick="closeModal()" style="background: none; border: none; cursor: pointer; color: var(--text-secondary); font-size: 1.5rem;">&times;</button>
        </div>
        
        <div id="modalLoading" style="display: none; padding: 60px; text-align: center;">
            <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary); margin-bottom: 16px;"></i>
            <p style="color: var(--text-secondary);">Loading payment details...</p>
        </div>
        
        <div id="modalContent" style="padding: 24px;">
            <!-- Customer Info -->
            <div style="margin-bottom: 32px;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    <i class="fas fa-user" style="margin-right: 8px; color: var(--primary);"></i> Customer Information
                </h3>
                <div class="grid grid-2" style="gap: 16px;">
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Name</div>
                        <div id="modalCustomerName" style="font-weight: 600; color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Email</div>
                        <div id="modalCustomerEmail" style="color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Phone</div>
                        <div id="modalCustomerPhone" style="color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Transaction ID</div>
                        <div id="modalTransactionId" style="font-family: monospace; color: var(--text-primary);">Loading...</div>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Details -->
            <div style="margin-bottom: 32px;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    <i class="fas fa-calendar-alt" style="margin-right: 8px; color: var(--primary);"></i> Reservation Details
                </h3>
                <div class="grid grid-2" style="gap: 16px;">
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Reservation ID</div>
                        <div id="modalReservationId" style="font-weight: 600; color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Car</div>
                        <div id="modalCarDetails" style="color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Rental Period</div>
                        <div id="modalRentalDates" style="color: var(--text-primary);">Loading...</div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div style="margin-bottom: 32px;">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">
                    <i class="fas fa-credit-card" style="margin-right: 8px; color: var(--primary);"></i> Payment Information
                </h3>
                <div class="grid grid-2" style="gap: 16px; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Amount</div>
                        <div id="modalAmount" style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Status</div>
                        <span id="modalStatus" class="status-badge">Loading...</span>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Payment Method</div>
                        <div id="modalPaymentMethod" style="color: var(--text-primary);">Loading...</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Date & Time</div>
                        <div id="modalPaymentDate" style="color: var(--text-primary);">Loading...</div>
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">Created On</div>
                    <div id="modalCreatedDate" style="color: var(--text-primary);">Loading...</div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 24px; border-top: 1px solid var(--border);">
                <button id="printReceiptBtn" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                <button onclick="closeModal()" class="btn btn-outline">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
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
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-failed {
    background: #fee2e2;
    color: #991b1b;
}

.status-refunded {
    background: #e5e7eb;
    color: #374151;
}

.grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.grid {
    display: grid;
}

.card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.btn-outline {
    background: transparent;
    color: var(--text-primary);
    border-color: var(--border);
}

.btn-outline:hover {
    background: var(--background);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

table tbody tr:hover {
    background: var(--background);
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr;
    }
    
    .card > div:first-child > div {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: repeat(2, 1fr);
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
        text-align: right;
    }
    
    table tbody td:before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-primary);
        display: block;
        margin-bottom: 4px;
        text-align: left;
    }

    table tbody td:last-child {
        text-align: center;
    }

    table tbody td:last-child:before {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .grid-4 {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const methodFilter = document.getElementById('methodFilter');
    const tableRows = document.querySelectorAll('.payment-row');
    const pagination = document.querySelector('.pagination-container');
    
    function filterPayments() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const methodValue = methodFilter.value;
        
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const transactionId = row.cells[0].textContent.toLowerCase();
            const customerName = row.cells[1].textContent.toLowerCase();
            const reservationInfo = row.cells[2].textContent.toLowerCase();
            const amount = row.cells[3].textContent.toLowerCase();
            const method = row.cells[4].textContent.toLowerCase();
            const statusBadge = row.cells[6].querySelector('.status-badge');
            const status = statusBadge ? statusBadge.textContent.toLowerCase() : '';
            
            const matchesSearch = transactionId.includes(searchTerm) || 
                                customerName.includes(searchTerm) || 
                                reservationInfo.includes(searchTerm) ||
                                amount.includes(searchTerm);
            const matchesStatus = !statusValue || status.includes(statusValue);
            const matchesMethod = !methodValue || method.includes(methodValue);
            
            if (matchesSearch && matchesStatus && matchesMethod) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Hide pagination when filtering
        if (pagination) {
            if (searchTerm || statusValue || methodValue) {
                pagination.style.display = 'none';
            } else {
                pagination.style.display = 'flex';
            }
        }
    }
    
    searchInput.addEventListener('input', filterPayments);
    statusFilter.addEventListener('change', filterPayments);
    methodFilter.addEventListener('change', filterPayments);
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('methodFilter').value = '';
    
    // Trigger filter to show all rows
    const event = new Event('input');
    document.getElementById('searchInput').dispatchEvent(event);
}

function viewPayment(paymentId) {
    window.location.href = '<?= base_url('/admin/viewPayment/') ?>' + paymentId;
}



// Change the printReceipt function in payments_management.php
function printReceipt(paymentId) {
    window.open('<?= base_url('/admin/printReceipt/') ?>' + paymentId, '_blank');
}

function exportPayments() {
    // Get current filter values
    const searchTerm = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const method = document.getElementById('methodFilter').value;
    
    // Build export URL with filters
    let exportUrl = '<?= base_url('/admin/exportPayments') ?>?';
    const params = new URLSearchParams();
    
    if (searchTerm) params.append('search', searchTerm);
    if (status) params.append('status', status);
    if (method) params.append('method', method);
    
    exportUrl += params.toString();
    window.location.href = exportUrl;
}
async function viewPayment(paymentId) {
    try {
        // Show loading state
        showModalLoading(true);
        
        // Fetch payment details via AJAX - CORRECTED URL
        const response = await fetch(`/admin/api/payments/${paymentId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'  // Added for CodeIgniter AJAX detection
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch payment details');
        }
        
        const payment = await response.json();
        
        // Check if there's an error in the response
        if (payment.error) {
            throw new Error(payment.error);
        }
        
        // Populate and show modal
        populatePaymentModal(payment);
        showModalLoading(false);
        document.getElementById('paymentModal').style.display = 'flex';
        
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load payment details: ' + error.message);
        showModalLoading(false);
    }
}

function showModalLoading(show) {
    const modalContent = document.getElementById('modalContent');
    const loading = document.getElementById('modalLoading');
    
    if (show) {
        modalContent.style.display = 'none';
        loading.style.display = 'block';
    } else {
        loading.style.display = 'none';
        modalContent.style.display = 'block';
    }
}

function populatePaymentModal(payment) {
    // Format dates
    const paymentDate = new Date(payment.payment_date);
    const createdDate = new Date(payment.created_at);
    
    // Populate modal content
    document.getElementById('modalTransactionId').textContent = payment.transaction_id || 'N/A';
    document.getElementById('modalCustomerName').textContent = payment.customer_name;
    document.getElementById('modalCustomerEmail').textContent = payment.email;
    document.getElementById('modalCustomerPhone').textContent = payment.phone || 'N/A';
    
    document.getElementById('modalReservationId').textContent = `#${String(payment.reservation_id).padStart(6, '0')}`;
    document.getElementById('modalCarDetails').textContent = `${payment.brand} ${payment.model} (${payment.plate_number || 'N/A'})`;
    document.getElementById('modalRentalDates').textContent = `${payment.start_date} to ${payment.end_date}`;
    
    document.getElementById('modalAmount').textContent = `₱${parseFloat(payment.amount).toFixed(2)}`;
    document.getElementById('modalPaymentMethod').textContent = formatPaymentMethod(payment.payment_method);
    document.getElementById('modalPaymentDate').textContent = paymentDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('modalCreatedDate').textContent = createdDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Set status badge
    const statusElement = document.getElementById('modalStatus');
    statusElement.textContent = payment.status.charAt(0).toUpperCase() + payment.status.slice(1);
    statusElement.className = `status-badge status-${payment.status}`;
    
    
    // Set print receipt button
    document.getElementById('printReceiptBtn').onclick = () => printReceipt(payment.id);
}

function formatPaymentMethod(method) {
    const methods = {
        'credit_card': 'Credit Card',
        'debit_card': 'Debit Card',
        'paypal': 'PayPal',
        'cash': 'Cash',
        'online': 'Online Payment'
    };
    return methods[method] || method;
}

function closeModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && document.getElementById('paymentModal').style.display === 'flex') {
        closeModal();
    }
});
</script>
<?= $this->endSection() ?>