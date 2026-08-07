<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations Report</title>
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
            grid-template-columns: repeat(5, 1fr);
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
        .stat-card.pending { border-left-color: #f39c12; }
        .stat-card.confirmed { border-left-color: #27ae60; }
        .stat-card.ongoing { border-left-color: #3498db; }
        .stat-card.completed { border-left-color: #7f8c8d; }
        .stat-card.cancelled { border-left-color: #e74c3c; }
        
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
        
        .status-pending { color: #f39c12; font-weight: bold; }
        .status-confirmed { color: #27ae60; font-weight: bold; }
        .status-ongoing { color: #3498db; font-weight: bold; }
        .status-completed { color: #7f8c8d; font-weight: bold; }
        .status-cancelled { color: #e74c3c; font-weight: bold; }
        
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
        
        .logo img {
            max-width: 150px;
            height: auto;
        }
        
        .filter-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 12px;
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
            <div class="report-title">Reservations Report</div>
            <div class="report-subtitle">Booking Analysis and Rental Performance</div>
            <div class="report-meta">
                Generated on: <?= date('F j, Y g:i A') ?><br>
                Period: <?= date('F j, Y', strtotime($start_date)) ?> to <?= date('F j, Y', strtotime($end_date)) ?>
            </div>
        </div>

        <!-- Filter Information -->
        <div class="filter-info">
            <strong>Report Filters:</strong><br>
            Date Range: <?= date('M j, Y', strtotime($start_date)) ?> - <?= date('M j, Y', strtotime($end_date)) ?><br>
            <?php if (!empty($current_filters['status']) && $current_filters['status'] !== 'all'): ?>
                Status: <?= ucfirst($current_filters['status']) ?><br>
            <?php endif; ?>
            <?php if (!empty($current_filters['search'])): ?>
                Search: "<?= esc($current_filters['search']) ?>"
            <?php endif; ?>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-number"><?= $reservation_stats['total_reservations'] ?? 0 ?></div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-number"><?= $reservation_stats['pending_reservations'] ?? 0 ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card confirmed">
                <div class="stat-number"><?= $reservation_stats['confirmed_reservations'] ?? 0 ?></div>
                <div class="stat-label">Confirmed</div>
            </div>
            <div class="stat-card ongoing">
                <div class="stat-number"><?= $reservation_stats['ongoing_reservations'] ?? 0 ?></div>
                <div class="stat-label">Active</div>
            </div>
            <div class="stat-card completed">
                <div class="stat-number"><?= $reservation_stats['completed_reservations'] ?? 0 ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="summary-section">
            <div class="summary-title">Financial Summary</div>
            <table>
                <tr>
                    <td><strong>Total Revenue:</strong></td>
                    <td class="text-right">₱<?= number_format($reservation_stats['total_revenue'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td><strong>Completed Reservations Revenue:</strong></td>
                    <td class="text-right">₱<?= number_format($reservation_stats['total_revenue'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                    <td><strong>Cancellation Rate:</strong></td>
                    <td class="text-right">
                        <?= $reservation_stats['total_reservations'] > 0 ? 
                            round((($reservation_stats['cancelled_reservations'] ?? 0) / $reservation_stats['total_reservations']) * 100, 1) : 0 ?>%
                    </td>
                </tr>
                <tr>
                    <td><strong>Completion Rate:</strong></td>
                    <td class="text-right">
                        <?= $reservation_stats['total_reservations'] > 0 ? 
                            round((($reservation_stats['completed_reservations'] ?? 0) / $reservation_stats['total_reservations']) * 100, 1) : 0 ?>%
                    </td>
                </tr>
            </table>
        </div>

        <!-- Active Reservations Table -->
        <div style="margin-top: 30px;">
            <div class="summary-title">Reservation Details</div>
            <table>
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Rental Period</th>
                        <th>Duration</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($active_reservations)): ?>
                        <?php foreach ($active_reservations as $reservation): ?>
                            <tr>
                                <td>#<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <strong><?= esc($reservation['customer_name']) ?></strong><br>
                                    <small><?= esc($reservation['email']) ?></small>
                                </td>
                                <td>
                                    <?= esc($reservation['brand']) ?> <?= esc($reservation['model']) ?><br>
                                    <small><?= esc($reservation['plate_number']) ?></small>
                                </td>
                                <td>
                                    <?= date('M j', strtotime($reservation['start_date'])) ?> - <?= date('M j, Y', strtotime($reservation['end_date'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $start = new DateTime($reservation['start_date']);
                                        $end = new DateTime($reservation['end_date']);
                                        $days = $start->diff($end)->days + 1;
                                        echo $days . ' day' . ($days > 1 ? 's' : '');
                                    ?>
                                </td>
                                <td class="text-right">₱<?= number_format($reservation['total_cost'], 2) ?></td>
                                <td>
                                    <span class="status-<?= $reservation['status'] ?>">
                                        <?= ucfirst($reservation['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No reservation data available for the selected period</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Status Distribution -->
        <div class="summary-section">
            <div class="summary-title">Status Distribution</div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
<!-- In the Status Distribution section, around line 387 -->
<?php
$statuses = [
    'pending' => ['count' => $reservation_stats['pending_reservations'] ?? 0, 'revenue' => 0],
    'confirmed' => ['count' => $reservation_stats['confirmed_reservations'] ?? 0, 'revenue' => 0],
    'ongoing' => ['count' => $reservation_stats['ongoing_reservations'] ?? 0, 'revenue' => 0],
    'completed' => ['count' => $reservation_stats['completed_reservations'] ?? 0, 'revenue' => $reservation_stats['total_revenue'] ?? 0],
    'cancelled' => ['count' => $reservation_stats['cancelled_reservations'] ?? 0, 'revenue' => 0]
];

// FIX: Ensure $total is never zero
$total = $reservation_stats['total_reservations'] ?? 1;
$total = max(1, $total); // Make sure it's at least 1 to avoid division by zero

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
    <td class="text-center"><?= round(($data['count'] / $total) * 100, 1) ?>%</td>
    <td class="text-right">₱<?= number_format($data['revenue'], 2) ?></td>
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