<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management Report</title>
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
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
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
        
        .status-available {
            color: #27ae60;
            font-weight: bold;
        }
        
        .status-unavailable {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .status-maintenance {
            color: #f39c12;
            font-weight: bold;
        }
        
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
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        <!-- Report Header -->
        <div class="report-header">
            <div class="logo">
                <!-- Add your logo here if needed -->
                <h2>CAR RENTAL SYSTEM</h2>
            </div>
            <div class="report-title">Car Management Report</div>
            <div class="report-subtitle">Fleet Overview and Vehicle Status</div>
            <div class="report-meta">
                Generated on: <?= date('F j, Y g:i A') ?><br>
                Period: <?= date('F Y') ?>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $car_stats['total_cars'] ?? 0 ?></div>
                <div class="stat-label">Total Cars</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $car_stats['available_cars'] ?? 0 ?></div>
                <div class="stat-label">Available</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $car_stats['unavailable_cars'] ?? 0 ?></div>
                <div class="stat-label">Unavailable</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $car_stats['maintenance_cars'] ?? 0 ?></div>
                <div class="stat-label">Maintenance</div>
            </div>
        </div>

        <!-- Car Details Table -->
        <table>
            <thead>
                <tr>
                    <th>Car Details</th>
                    <th>Specifications</th>
                    <th>Daily Rate</th>
                    <th>Status</th>
                    <th>Plate Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($popular_cars)): ?>
                    <?php foreach ($popular_cars as $car): ?>
                        <tr>
                            <td>
                                <strong><?= esc($car['brand']) ?> <?= esc($car['model']) ?></strong><br>
                                <small>Year: <?= $car['year'] ?? 'N/A' ?></small>
                            </td>
                            <td>
                                <?= $car['color'] ?? 'N/A' ?><br>
                                <?= $car['capacity'] ?? 'N/A' ?> seats • <?= ucfirst($car['transmission'] ?? 'N/A') ?>
                            </td>
                            <td class="text-right">
                                ₱<?= number_format($car['daily_rate'] ?? 0, 2) ?>
                            </td>
                            <td>
                                <span class="status-<?= $car['status'] ?? 'unavailable' ?>">
                                    <?= ucfirst($car['status'] ?? 'Unavailable') ?>
                                </span>
                            </td>
                            <td><?= $car['plate_number'] ?? 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No car data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Popular Cars Section -->
        <?php if (!empty($car_stats['popular_cars'])): ?>
            <div class="summary-section">
                <div class="summary-title">Top Performing Cars (by Reservations)</div>
                <table>
                    <thead>
                        <tr>
                            <th>Car</th>
                            <th>Total Reservations</th>
                            <th>Estimated Revenue</th>
                            <th>Utilization Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($car_stats['popular_cars'] as $popular_car): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($popular_car['brand']) ?> <?= esc($popular_car['model']) ?></strong>
                                </td>
                                <td class="text-center"><?= $popular_car['reservation_count'] ?></td>
                                <td class="text-right">
                                    ₱<?= number_format($popular_car['total_revenue'] ?? 0, 2) ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $utilization = isset($popular_car['reservation_count']) ? 
                                            min(($popular_car['reservation_count'] / max($car_stats['total_cars'], 1)) * 100, 100) : 0;
                                        echo round($utilization, 1) . '%';
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-title">Fleet Summary</div>
            <table>
                <tr>
                    <td><strong>Total Fleet Value:</strong></td>
                    <td class="text-right">
                        ₱<?= number_format(($car_stats['total_cars'] ?? 0) * 25000, 2) ?> (estimated)
                    </td>
                </tr>
                <tr>
                    <td><strong>Availability Rate:</strong></td>
                    <td class="text-right">
                        <?= $car_stats['total_cars'] > 0 ? 
                            round((($car_stats['available_cars'] ?? 0) / $car_stats['total_cars']) * 100, 1) : 0 ?>%
                    </td>
                </tr>
                <tr>
                    <td><strong>Maintenance Rate:</strong></td>
                    <td class="text-right">
                        <?= $car_stats['total_cars'] > 0 ? 
                            round((($car_stats['maintenance_cars'] ?? 0) / $car_stats['total_cars']) * 100, 1) : 0 ?>%
                    </td>
                </tr>
                <tr>
                    <td><strong>Average Daily Rate:</strong></td>
                    <td class="text-right">
                        ₱<?= number_format(($car_stats['total_cars'] ?? 0) > 0 ? 
                            (array_sum(array_column($popular_cars ?? [], 'daily_rate')) / count($popular_cars ?? [])) : 0, 2) ?>
                    </td>
                </tr>
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