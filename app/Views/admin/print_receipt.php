<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - <?= str_pad($payment['id'], 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.3;
            color: #000;
            background: white;
            padding: 15px;
            font-size: 12px;
        }

        .receipt {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #000;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }

        .receipt-title {
            background: #f8f9fa;
            padding: 8px;
            text-align: center;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .receipt-title h2 {
            font-size: 14px;
            color: #000;
        }

        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-section {
            padding: 10px;
            border: 1px solid #ddd;
            background: #fafafa;
        }

        .info-section h3 {
            font-size: 11px;
            margin-bottom: 8px;
            color: #000;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .info-row .label {
            font-weight: 600;
            color: #555;
        }

        .info-row .value {
            color: #000;
            text-align: right;
        }

        .payment-summary {
            margin: 15px 0;
            padding: 12px;
            border: 1px solid #000;
            background: #fff;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            border-top: 2px solid #000;
            border-bottom: none;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            margin-top: 8px;
            padding-top: 8px;
        }

        .terms {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #888;
        }

        .status-completed {
            color: #065f46;
            font-weight: 600;
        }

        .status-pending {
            color: #92400e;
            font-weight: 600;
        }

        .status-failed {
            color: #991b1b;
            font-weight: 600;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .receipt {
                border: none;
                padding: 15px;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .receipt-info {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            body {
                padding: 10px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>CAR RENTAL SYSTEM</h1>
            <p>123 Rental Street, City, State 12345</p>
            <p>Phone: (555) 123-4567 | Email: info@carrental.com</p>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            <h2>PAYMENT RECEIPT</h2>
        </div>

        <!-- Receipt Information Grid -->
        <div class="receipt-info">
            <!-- Payment Information -->
            <div class="info-section">
                <h3>PAYMENT DETAILS</h3>
                <div class="info-row">
                    <span class="label">Receipt #:</span>
                    <span class="value"><?= str_pad($payment['id'], 6, '0', STR_PAD_LEFT) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Transaction ID:</span>
                    <span class="value"><?= $payment['transaction_id'] ?: 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date:</span>
                    <span class="value"><?= date('M j, Y g:i A', strtotime($payment['payment_date'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Method:</span>
                    <span class="value"><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value status-<?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="info-section">
                <h3>CUSTOMER INFORMATION</h3>
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value"><?= $payment['customer_name'] ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?= $payment['email'] ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Reservation #:</span>
                    <span class="value">#<?= str_pad($payment['reservation_id'], 6, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="info-section">
            <h3>VEHICLE & RENTAL DETAILS</h3>
            <div class="info-row">
                <span class="label">Vehicle:</span>
                <span class="value"><?= $payment['brand'] ?> <?= $payment['model'] ?></span>
            </div>
            <div class="info-row">
                <span class="label">License Plate:</span>
                <span class="value"><?= $payment['plate_number'] ?></span>
            </div>
            <div class="info-row">
                <span class="label">Rental Period:</span>
                <span class="value">
                    <?= date('M j, Y', strtotime($reservation['start_date'])) ?> - <?= date('M j, Y', strtotime($reservation['end_date'])) ?>
                </span>
            </div>
            <div class="info-row">
                <span class="label">Duration:</span>
                <span class="value"><?= $rentalDays ?> day<?= $rentalDays > 1 ? 's' : '' ?></span>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <h3 style="text-align: center; margin-bottom: 10px; font-size: 12px;">PAYMENT SUMMARY</h3>
            <div class="summary-row">
                <span class="label">Rental Cost:</span>
                <span class="value">₱<?= number_format($reservation['total_cost'], 2) ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Tax (10%):</span>
                <span class="value">₱<?= number_format($taxAmount, 2) ?></span>
            </div>
            <div class="summary-row total">
                <span class="label">TOTAL PAID:</span>
                <span class="value">₱<?= number_format($payment['amount'], 2) ?></span>
            </div>
        </div>

        <!-- Terms -->
        <div class="terms">
            <p><strong>Thank you for your business!</strong> This receipt serves as proof of payment. For inquiries, contact customer service.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Receipt generated on: <?= date('M j, Y \a\t g:i A') ?></p>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });

        // Close window after printing
        window.addEventListener('afterprint', function() {
            setTimeout(function() {
                window.close();
            }, 100);
        });
    </script>
</body>
</html>