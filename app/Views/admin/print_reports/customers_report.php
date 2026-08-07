<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Report</title>
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
        
        .stat-card.total { border-left-color: #2c3e50; }
        .stat-card.active { border-left-color: #27ae60; }
        .stat-card.avg { border-left-color: #f39c12; }
        .stat-card.new { border-left-color: #8b5cf6; }
        
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
        
        .status-active { color: #27ae60; font-weight: bold; }
        .status-inactive { color: #e74c3c; font-weight: bold; }
        
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
        
        .customer-avatar {
            width: 30px;
            height: 30px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
            margin-right: 8px;
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
            <div class="report-title">Customers Report</div>
            <div class="report-subtitle">Customer Base Analysis and Activity Overview</div>
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

        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-number"><?= $customer_stats['total_customers'] ?? 0 ?></div>
                <div class="stat-label">Total Customers</div>
            </div>
            <div class="stat-card active">
                <div class="stat-number"><?= $customer_stats['active_customers'] ?? 0 ?></div>
                <div class="stat-label">Active Customers</div>
            </div>
            <div class="stat-card avg">
                <div class="stat-number"><?= $customer_stats['avg_reservations'] ?? 0 ?></div>
                <div class="stat-label">Avg. Reservations</div>
            </div>
            <div class="stat-card new">
                <div class="stat-number"><?= $customer_stats['new_customers'] ?? 0 ?></div>
                <div class="stat-label">New Customers</div>
            </div>
        </div>

        <!-- Customer Demographics -->
        <div class="summary-section">
            <div class="summary-title">Customer Demographics</div>
            <table>
                <tr>
                    <td><strong>Total Customer Base:</strong></td>
                    <td class="text-right"><?= $customer_stats['total_customers'] ?? 0 ?> customers</td>
                </tr>
                <tr>
                    <td><strong>Active Customers (with reservations):</strong></td>
                    <td class="text-right"><?= $customer_stats['active_customers'] ?? 0 ?> (<?= $customer_stats['total_customers'] > 0 ? round((($customer_stats['active_customers'] ?? 0) / $customer_stats['total_customers']) * 100, 1) : 0 ?>%)</td>
                </tr>
                <tr>
                    <td><strong>New Customers This Period:</strong></td>
                    <td class="text-right"><?= $customer_stats['new_customers'] ?? 0 ?></td>
                </tr>
                <tr>
                    <td><strong>Average Reservations per Customer:</strong></td>
                    <td class="text-right"><?= number_format($customer_stats['avg_reservations'] ?? 0, 1) ?></td>
                </tr>
                <tr>
                    <td><strong>Customer Growth Rate:</strong></td>
                    <td class="text-right">
                        <?php
                            $growthRate = $customer_stats['total_customers'] > 0 ? 
                                (($customer_stats['new_customers'] ?? 0) / $customer_stats['total_customers']) * 100 : 0;
                            echo round($growthRate, 1) . '%';
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Customer Details Table -->
        <div style="margin-top: 30px;">
            <div class="summary-title">Customer Details</div>
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Join Date</th>
                        <th>Reservations</th>
                        <th>Total Spent</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($new_customers)): ?>
                        <?php foreach ($new_customers as $customer): ?>
                            <?php 
                            $customerStats = $userModel->getCustomerStats($customer['id']);
                            $isActive = $customerStats['total_reservations'] > 0;
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="customer-avatar">
                                            <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong><?= esc($customer['name']) ?></strong><br>
                                            <small>ID: <?= str_pad($customer['id'], 6, '0', STR_PAD_LEFT) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= esc($customer['email']) ?><br>
                                    <small><?= esc($customer['phone'] ?? 'N/A') ?></small>
                                </td>
                                <td><?= date('M j, Y', strtotime($customer['created_at'])) ?></td>
                                <td class="text-center"><?= $customerStats['total_reservations'] ?></td>
                                <td class="text-right">₱<?= number_format($customerStats['total_spent'], 2) ?></td>
                                <td>
                                    <span class="status-<?= $isActive ? 'active' : 'inactive' ?>">
                                        <?= $isActive ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No customer data available for the selected period</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Top Customers by Spending -->
        <?php if (!empty($customer_stats['top_customers'])): ?>
            <div class="summary-section">
                <div class="summary-title">Top Customers by Spending</div>
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Reservations</th>
                            <th>Total Spent</th>
                            <th>Avg. per Reservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($customer_stats['top_customers'], 0, 5) as $customer): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($customer['name']) ?></strong><br>
                                    <small><?= esc($customer['email']) ?></small>
                                </td>
                                <td class="text-center"><?= $customer['reservation_count'] ?></td>
                                <td class="text-right">$<?= number_format($customer['total_spent'], 2) ?></td>
                                <td class="text-right">
                                    $<?= number_format($customer['reservation_count'] > 0 ? $customer['total_spent'] / $customer['reservation_count'] : 0, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

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