<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Report</title>
    <style>
        /* Print-friendly styles */
        @media print {
            @page {
                margin: 0.5in;
                size: letter;
            }
            
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                color: #000;
                background: white;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        /* Screen styles */
        @media screen {
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: #333;
                max-width: 8.5in;
                margin: 0 auto;
                padding: 20px;
                background: #f5f5f5;
            }
            
            .print-container {
                background: white;
                padding: 40px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                border-radius: 8px;
            }
        }
        
        /* Common styles */
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .report-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .report-meta {
            font-size: 12px;
            color: #95a5a6;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            border-left: 4px solid #3498db;
        }
        
        .stat-card.revenue { border-left-color: #27ae60; }
        .stat-card.monthly { border-left-color: #3498db; }
        .stat-card.pending { border-left-color: #f39c12; }
        .stat-card.failed { border-left-color: #e74c3c; }
        .stat-card.avg { border-left-color: #8b5cf6; }
        .stat-card.success { border-left-color: #10b981; }
        .stat-card.transactions { border-left-color: #6b7280; }
        .stat-card.refunds { border-left-color: #ef4444; }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #34495e;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .status-completed { color: #27ae60; font-weight: bold; }
        .status-pending { color: #f39c12; font-weight: bold; }
        .status-failed { color: #e74c3c; font-weight: bold; }
        .status-refunded { color: #6b7280; font-weight: bold; }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: #ecf0f1;
            border-radius: 6px;
        }
        
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .print-actions {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .btn {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .filter-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .method-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <!-- Report Header -->
        <div class="report-header">
            <div class="logo">
                <h2>CAR RENTAL SYSTEM</h2>
            </div>
            <div class="report-title">Payments Report</div>
            <div class="report-subtitle">Financial Transactions and Revenue Analysis</div>
            <div class="report-meta">
                Generated on: <?= date('F j, Y g:i A') ?><br>
                Period: <?= date('F j, Y', strtotime($start_date)) ?> to <?= date('F j, Y', strtotime($end_date)) ?>
            </div>
        </div>

        <!-- Filter Information -->
        <div class="filter-info">
            <strong>Report Filters:</strong><br>
            Date Range: <?= date('M j, Y', strtotime($start_date)) ?> - <?= date('M j, Y', strtotime($end_date)) ?>
        </div>

        <!-- Financial Overview -->
        <div class="stats-grid">
            <div class="stat-card revenue">
                <div class="stat-number">₱<?= number_format($payment_stats['total_revenue'] ?? 0, 2) ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card monthly">
                <div class="stat-number">₱<?= number_format($payment_stats['monthly_revenue'] ?? 0, 2) ?></div>
                <div class="stat-label">Period Revenue</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-number">₱<?= number_format($payment_stats['pending_amount'] ?? 0, 2) ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card failed">
                <div class="stat-number">₱<?= number_format($payment_stats['failed_amount'] ?? 0, 2) ?></div>
                <div class="stat-label">Failed</div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="stats-grid">
            <div class="stat-card avg">
                <div class="stat-number">₱<?= number_format($payment_stats['avg_transaction'] ?? 0, 2) ?></div>
                <div class="stat-label">Avg. Transaction</div>
            </div>
            <div class="stat-card success">
                <div class="stat-number"><?= $payment_stats['success_rate'] ?? 0 ?>%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-card transactions">
                <div class="stat-number"><?= $payment_stats['total_transactions'] ?? 0 ?></div>
                <div class="stat-label">Transactions</div>
            </div>
            <div class="stat-card refunds">
                <div class="stat-number"><?= $payment_stats['refund_count'] ?? 0 ?></div>
                <div class="stat-label">Refunds</div>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="summary-section">
            <div class="summary-title">Payment Method Distribution</div>
            <table>
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th>Transactions</th>
                        <th>Total Amount</th>
                        <th>Percentage</th>
                        <th>Avg. Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payment_methods)): ?>
                        <?php foreach ($payment_methods as $method): ?>
                            <tr>
                                <td>
                                    <div class="method-badge">
                                        <?php if ($method['payment_method'] === 'credit_card'): ?>
                                            <i class="fab fa-cc-visa"></i> Credit Card
                                        <?php elseif ($method['payment_method'] === 'debit_card'): ?>
                                            <i class="fas fa-credit-card"></i> Debit Card
                                        <?php elseif ($method['payment_method'] === 'paypal'): ?>
                                            <i class="fab fa-paypal"></i> PayPal
                                        <?php elseif ($method['payment_method'] === 'cash'): ?>
                                            <i class="fas fa-money-bill-wave"></i> Cash
                                        <?php else: ?>
                                            <i class="fas fa-credit-card"></i> <?= ucfirst(str_replace('_', ' ', $method['payment_method'])) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center"><?= $method['count'] ?></td>
                                <td class="text-right">₱<?= number_format($method['total_amount'], 2) ?></td>
                                <td class="text-center">
                                    <?= $payment_stats['total_revenue'] > 0 ? 
                                        round(($method['total_amount'] / $payment_stats['total_revenue']) * 100, 1) : 0 ?>%
                                </td>
                                <td class="text-right">₱<?= number_format($method['count'] > 0 ? $method['total_amount'] / $method['count'] : 0, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No payment method data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

<!-- Transaction Details -->
<div style="margin-top: 30px;">
    <div class="summary-title">Recent Transactions</div>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Customer</th>
                <th>Reservation</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_payments)): ?>
                <?php foreach ($recent_payments as $payment): ?>
                    <tr>
                        <td><?= $payment['transaction_id'] ?: 'N/A' ?></td>
                        <td>
                            <?= $payment['customer_name'] ?><br>
                            <small><?= $payment['email'] ?></small>
                        </td>
                        <td>#<?= str_pad($payment['reservation_id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td class="text-right">₱<?= number_format($payment['amount'], 2) ?></td>
                        <td>
                            <span class="method-badge">
                                <?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?>
                            </span>
                        </td>
                        <td><?= date('M j, Y', strtotime($payment['payment_date'])) ?></td>
                        <td>
                            <span class="status-<?= $payment['status'] ?>">
                                <?= ucfirst($payment['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No transaction data available for the selected period</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

        <!-- Status Summary -->
        <div class="summary-section">
            <div class="summary-title">Transaction Status Summary</div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Total Amount</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statuses = [
                        'completed' => ['count' => $payment_stats['completed_payments'] ?? 0, 'amount' => $payment_stats['total_revenue'] ?? 0],
                        'pending' => ['count' => $payment_stats['pending_payments'] ?? 0, 'amount' => $payment_stats['pending_amount'] ?? 0],
                        'failed' => ['count' => $payment_stats['failed_payments'] ?? 0, 'amount' => $payment_stats['failed_amount'] ?? 0],
                        'refunded' => ['count' => $payment_stats['refund_count'] ?? 0, 'amount' => 0]
                    ];
                    
                    $totalCount = $payment_stats['total_payments'] ?? 1;
                    
                    foreach ($statuses as $status => $data):
                        if ($data['count'] > 0):
                    ?>
                    <tr>
                        <td>
                            <span class="status-<?= $status ?>">
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                        <td class="text-center"><?= $data['count'] ?></td>
                        <td class="text-right">₱<?= number_format($data['amount'], 2) ?></td>
                        <td class="text-center"><?= $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 1) : 0 ?>%</td>
                    </tr>
                    <?php endif; endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #7f8c8d;">
            This report was automatically generated by Car Rental System on <?= date('F j, Y') ?><br>
            Page 1 of 1
        </div>

        <!-- Print Actions (Visible only on screen) -->
        <div class="print-actions no-print">
            <button class="btn" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button class="btn" onclick="window.close()">
                <i class="fas fa-times"></i> Close Window
            </button>
        </div>
    </div>

    <script>
        // Auto-close window after print (optional)
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        };

        // Fallback for browsers that don't support onafterprint
        setTimeout(function() {
            window.close();
        }, 30000); // Close after 30 seconds if not printed
    </script>
</body>
</html>