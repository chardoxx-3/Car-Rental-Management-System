<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>

<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Analytics & Reports</h1>
        <p style="color: var(--text-secondary);">Comprehensive business insights and performance metrics</p>
    </div>

<!-- Replace the Date Range Selector section in reports.php -->
<div class="card" style="margin-bottom: 32px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 16px; align-items: end;">
        <div class="form-group">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" id="startDate" value="<?= $start_date ?>">
        </div>
        <div class="form-group">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" id="endDate" value="<?= $end_date ?>">
        </div>
        <div class="form-group">
            <label class="form-label" style="visibility: hidden;">Apply</label>
            <button class="btn btn-primary" onclick="applyFilters()" style="white-space: nowrap;">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
        </div>
        <div class="form-group">
            <label class="form-label" style="visibility: hidden;">Reset</label>
            <button class="btn btn-secondary" onclick="resetFilters()" style="white-space: nowrap;">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>
</div>

    <!-- Key Metrics - FIXED ACTIVE CUSTOMERS -->
    <div class="grid grid-4" style="margin-bottom: 32px;">
        <div class="card text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 4px;">
                ₱<?= number_format($reports['payment_stats']['total_revenue'] ?? 0, 2) ?>
            </div>
            <div style="color: var(--text-secondary);">Total Revenue</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px;">
                <?php $revenueChange = $revenue_change ?? 0; ?>
                <i class="fas fa-arrow-<?= $revenueChange >= 0 ? 'up' : 'down' ?>" 
                   style="color: <?= $revenueChange >= 0 ? 'var(--success)' : 'var(--danger)' ?>; font-size: 12px;"></i>
                <span style="color: <?= $revenueChange >= 0 ? 'var(--success)' : 'var(--danger)' ?>; font-size: 0.875rem; font-weight: 600;">
                    <?= abs($revenueChange) ?>%
                </span>
            </div>
        </div>

        <div class="card text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 4px;">
                <?= $reports['payment_stats']['completed_payments'] ?? 0 ?>
            </div>
            <div style="color: var(--text-secondary);">Completed Payments</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px;">
                <i class="fas fa-arrow-up" style="color: var(--success); font-size: 12px;"></i>
                <span style="color: var(--success); font-size: 0.875rem; font-weight: 600;">
                    <?= $reports['payment_stats']['total_payments'] > 0 ? 
                        round(($reports['payment_stats']['completed_payments'] / $reports['payment_stats']['total_payments']) * 100, 1) : 0 ?>%
                </span>
            </div>
        </div>

        <div class="card text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 4px;">
                <?= $active_customers ?? 0 ?>
            </div>
            <div style="color: var(--text-secondary);">Active Customers</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px;">
                <i class="fas fa-users" style="color: var(--warning); font-size: 12px;"></i>
                <span style="color: var(--warning); font-size: 0.875rem; font-weight: 600;">
                    In selected period
                </span>
            </div>
        </div>

        <div class="card text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--secondary); margin-bottom: 4px;">
                <?= $reports['car_stats']['available_cars'] ?? 0 ?>/<?= $reports['car_stats']['total_cars'] ?? 0 ?>
            </div>
            <div style="color: var(--text-secondary);">Available Cars</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 8px;">
                <i class="fas fa-car" style="color: var(--secondary); font-size: 12px;"></i>
                <span style="color: var(--secondary); font-size: 0.875rem; font-weight: 600;">
                    <?= $reports['car_stats']['total_cars'] > 0 ? 
                        round(($reports['car_stats']['available_cars'] / $reports['car_stats']['total_cars']) * 100) : 0 ?>%
                </span>
            </div>
        </div>
    </div>

    <!-- Rest of your view remains the same but will now show proper data -->
    <!-- Charts and Reports -->
    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 32px; margin-bottom: 32px;">
        <!-- Revenue Chart -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">Revenue Trend</h3>
                <select class="form-control" id="chartPeriod" style="width: auto; padding: 8px 16px;" onchange="updateChart()">
                    <option value="6">Last 6 Months</option>
                    <option value="12" selected>Last 12 Months</option>
                    <option value="ytd">Year to Date</option>
                </select>
            </div>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Performing Cars - NOW SHOWS PROPER DATA -->
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Top Performing Cars</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <?php if (!empty($reports['popular_cars'])): ?>
                    <?php foreach (array_slice($reports['popular_cars'], 0, 5) as $car): ?>
                        <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--background); border-radius: 8px;">
                            <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-car" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 500; color: var(--text-primary);"><?= esc($car['brand']) ?> <?= esc($car['model']) ?></div>
                                <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= $car['reservation_count'] ?> rentals</div>
                            </div>
                            <div style="font-weight: 600; color: var(--primary);">$<?= number_format($car['total_revenue'] ?? 0, 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                        <i class="fas fa-car" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                        No rental data available for selected period
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Breakdown - NOW SHOWS RESERVATION COUNTS -->
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 32px;">
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Monthly Revenue</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Month</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-primary);">Revenue</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-primary);">Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reports['monthly_revenue'])): ?>
                            <?php foreach ($reports['monthly_revenue'] as $monthly): ?>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 12px; color: var(--text-primary);">
                                        <?= date('F Y', strtotime($monthly['month'] . '-01')) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: right; font-weight: 600; color: var(--primary);">
                                        ₱<?= number_format($monthly['revenue'], 2) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: right; color: var(--text-primary);">
                                        <?= $monthly['reservation_count'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="padding: 40px 12px; text-align: center; color: var(--text-secondary);">
                                    No revenue data available for selected period
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Customers - NOW SHOWS PROPER DATA -->
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Top Customers</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: var(--text-primary);">Customer</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-primary);">Spent</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: var(--text-primary);">Rentals</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reports['top_customers'])): ?>
                            <?php foreach (array_slice($reports['top_customers'], 0, 5) as $customer): ?>
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 12px;">
                                        <div style="font-weight: 500; color: var(--text-primary);"><?= esc($customer['name']) ?></div>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= esc($customer['email']) ?></div>
                                    </td>
                                    <td style="padding: 12px; text-align: right; font-weight: 600; color: var(--primary);">
                                        ₱<?= number_format($customer['total_spent'], 2) ?>
                                    </td>
                                    <td style="padding: 12px; text-align: right; color: var(--text-primary);">
                                        <?= $customer['reservation_count'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="padding: 40px 12px; text-align: center; color: var(--text-secondary);">
                                    No customer data available for selected period
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Export Options -->
<div class="card" style="margin-top: 32px;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px;">Export Reports</h3>
    
    <!-- Report Type Selection -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <!-- Car Management Report -->
        <div class="card" style="padding: 20px; border: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-car" style="color: white; font-size: 16px;"></i>
                </div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">Car Management Report</h4>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                Comprehensive overview of fleet management including car availability, maintenance status, 
                utilization rates, and performance metrics for all vehicles in your inventory.
            </p>
            <button class="btn btn-primary" onclick="printReport('car-management')" style="width: 100%;">
                <i class="fas fa-print"></i> Print Car Report
            </button>
        </div>

        <!-- Reservations Report -->
        <div class="card" style="padding: 20px; border: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; background: var(--success); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-check" style="color: white; font-size: 16px;"></i>
                </div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">Reservations Report</h4>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                Detailed analysis of booking patterns, reservation status, duration statistics, 
                and occupancy rates to optimize your rental scheduling and availability.
            </p>
            <br>
            <button class="btn btn-success" onclick="printReport('reservations')" style="width: 100%;">
                <i class="fas fa-print"></i> Print Reservations Report
            </button>
        </div>

        <!-- Customers Report -->
        <div class="card" style="padding: 20px; border: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; background: var(--warning); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: white; font-size: 16px;"></i>
                </div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">Customers Report</h4>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                Customer behavior insights including rental frequency, spending patterns, 
                loyalty metrics, and demographic information for targeted marketing strategies.
            </p>
            <button class="btn btn-warning" onclick="printReport('customers')" style="width: 100%;">
                <i class="fas fa-print"></i> Print Customers Report
            </button>
        </div>

        <!-- Payments Report -->
        <div class="card" style="padding: 20px; border: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; background: var(--info); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-credit-card" style="color: white; font-size: 16px;"></i>
                </div>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin: 0;">Payments Report</h4>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                Financial overview including revenue streams, payment method distribution, 
                transaction success rates, refund analysis, and cash flow monitoring.
            </p>
            <br>
            <button class="btn btn-info" onclick="printReport('payments')" style="width: 100%;">
                <i class="fas fa-print"></i> Print Payments Report
            </button>
        </div>
    </div>
</div>
</div>

<!-- Your existing CSS and JavaScript remain the same -->
<style>
@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid[style*="grid-template-columns: 2fr 1fr"],
    .grid[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr;
    }
    
    .grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: 1fr;
    }
    
    .card > div:first-child > div {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .card > div:first-child > div button {
        width: 100%;
    }
}

table {
    width: 100%;
}

table th, table td {
    padding: 12px;
    text-align: left;
}

table tbody tr:hover {
    background: var(--background);
}

/* Add to your existing CSS */
.card > div:first-child > div button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.card > div:first-child > div button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card > div:first-child > div button:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .card > div:first-child > div {
        grid-template-columns: 1fr;
    }
    
    .card > div:first-child > div button {
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Your existing JavaScript remains the same
let revenueChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    fetchChartData().then(data => {
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Revenue',
                    data: data.values,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `Revenue: ₱${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    });
}

async function fetchChartData(period = '12') {
    try {
        const response = await fetch('/admin/getRevenueData?days=' + (period === 'ytd' ? '365' : period * 30));
        return await response.json();
    } catch (error) {
        console.error('Error fetching chart data:', error);
        return { labels: [], values: [] };
    }
}

function updateChart() {
    const period = document.getElementById('chartPeriod').value;
    fetchChartData(period).then(data => {
        revenueChart.data.labels = data.labels;
        revenueChart.data.datasets[0].data = data.values;
        revenueChart.update();
    });
}

function generateReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const reportType = document.getElementById('reportType').value;
    
    const generateBtn = document.querySelector('button[onclick="generateReport()"]');
    const originalText = generateBtn.innerHTML;
    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    generateBtn.disabled = true;
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        report_type: reportType
    });
    
    window.location.href = '/admin/reports?' + params.toString();
}

function exportReport(format) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const reportType = document.getElementById('reportType').value;
    
    const url = `/admin/exportReport?format=${format}&start_date=${startDate}&end_date=${endDate}&report_type=${reportType}`;
    window.open(url, '_blank');
}

function printReport(reportType) {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Show loading state
    const buttons = document.querySelectorAll(`button[onclick="printReport('${reportType}')"]`);
    buttons.forEach(button => {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        button.disabled = true;
        
        // Restore button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    });
    
    // Generate report URL based on type
    let reportUrl;
    
    switch(reportType) {
        case 'car-management':
            reportUrl = `/admin/printCarManagementReport?start_date=${startDate}&end_date=${endDate}`;
            break;
        case 'reservations':
            reportUrl = `/admin/printReservationsReport?start_date=${startDate}&end_date=${endDate}`;
            break;
        case 'customers':
            reportUrl = `/admin/printCustomersReport?start_date=${startDate}&end_date=${endDate}`;
            break;
        case 'payments':
            reportUrl = `/admin/printPaymentsReport?start_date=${startDate}&end_date=${endDate}`;
            break;
        default:
            reportUrl = `/admin/printReport?type=${reportType}&start_date=${startDate}&end_date=${endDate}`;
    }
    
    // Open in new window with print dialog
    const printWindow = window.open(reportUrl, '_blank', 'width=1000,height=600');
    
    // The print will automatically trigger due to onload="window.print()" in the report
}

// Fallback print function for the entire page
function printEntireReport() {
    window.print();
}

// Add these functions to your existing JavaScript

function applyFilters() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Validate dates
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    if (startDate > endDate) {
        alert('Start date cannot be after end date');
        return;
    }
    
    // Show loading state
    const applyBtn = document.querySelector('button[onclick="applyFilters()"]');
    const originalText = applyBtn.innerHTML;
    applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
    applyBtn.disabled = true;
    
    // Build URL with filters
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        report_type: '<?= $report_type ?>'
    });
    
    // Navigate to the filtered reports page
    window.location.href = '/admin/reports?' + params.toString();
}

function resetFilters() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    // Format dates as YYYY-MM-DD
    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };
    
    document.getElementById('startDate').value = formatDate(firstDay);
    document.getElementById('endDate').value = formatDate(lastDay);
    
    // Apply the reset filters immediately
    applyFilters();
}

// Add keyboard shortcut support
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        // Ctrl+Enter or Cmd+Enter to apply filters
        applyFilters();
    }
    if (e.key === 'Escape') {
        // Escape to reset filters
        resetFilters();
    }
});

// Add input validation for date fields
document.getElementById('startDate').addEventListener('change', function() {
    const endDate = document.getElementById('endDate').value;
    if (endDate && this.value > endDate) {
        alert('Start date cannot be after end date');
        this.value = endDate;
    }
});

document.getElementById('endDate').addEventListener('change', function() {
    const startDate = document.getElementById('startDate').value;
    if (startDate && this.value < startDate) {
        alert('End date cannot be before start date');
        this.value = startDate;
    }
});

// Update chart when dates change (optional - for real-time updates)
let chartUpdateTimeout;
document.getElementById('startDate').addEventListener('change', function() {
    clearTimeout(chartUpdateTimeout);
    chartUpdateTimeout = setTimeout(updateChartFromDates, 500);
});

document.getElementById('endDate').addEventListener('change', function() {
    clearTimeout(chartUpdateTimeout);
    chartUpdateTimeout = setTimeout(updateChartFromDates, 500);
});

// Optional: Function to update chart without page reload
async function updateChartFromDates() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate || startDate > endDate) {
        return;
    }
    
    try {
        // You might want to implement an API endpoint for this
        // For now, we'll just reload the page
        console.log('Dates changed, chart update triggered');
    } catch (error) {
        console.error('Error updating chart:', error);
    }
}
</script>
<?= $this->endSection() ?>