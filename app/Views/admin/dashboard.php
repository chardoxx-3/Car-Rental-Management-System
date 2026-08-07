<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Admin Dashboard</h1>
        <p style="color: var(--text-secondary);">Welcome back! Here's what's happening with your business today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-4" style="margin-bottom: 40px;">
        <div class="card" style="border-left: 4px solid var(--primary);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Total Revenue</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">₱<?= number_format($stats['total_revenue'] ?? 0, 2) ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-dollar-sign" style="color: var(--primary); font-size: 20px;"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 12px;">
                <?php 
                $revenueChange = $stats['revenue_change'] ?? 0;
                $revenueIcon = $revenueChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                $revenueColor = $revenueChange >= 0 ? 'var(--success)' : 'var(--error)';
                ?>
                <i class="fas <?= $revenueIcon ?>" style="color: <?= $revenueColor ?>; font-size: 12px;"></i>
                <span style="color: <?= $revenueColor ?>; font-size: 0.875rem; font-weight: 600;"><?= number_format(abs($revenueChange), 1) ?>%</span>
                <span style="color: var(--text-secondary); font-size: 0.875rem;">from last month</span>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--success);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Total Reservations</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total_reservations'] ?? 0 ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-check" style="color: var(--success); font-size: 20px;"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 12px;">
                <?php 
                $reservationsChange = $stats['reservations_change'] ?? 0;
                $reservationsIcon = $reservationsChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                $reservationsColor = $reservationsChange >= 0 ? 'var(--success)' : 'var(--error)';
                ?>
                <i class="fas <?= $reservationsIcon ?>" style="color: <?= $reservationsColor ?>; font-size: 12px;"></i>
                <span style="color: <?= $reservationsColor ?>; font-size: 0.875rem; font-weight: 600;"><?= number_format(abs($reservationsChange), 1) ?>%</span>
                <span style="color: var(--text-secondary); font-size: 0.875rem;">from last month</span>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--warning);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Available Cars</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);"><?= $stats['available_cars'] ?? 0 ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-car" style="color: var(--warning); font-size: 20px;"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 12px;">
                <i class="fas fa-info-circle" style="color: var(--text-secondary); font-size: 12px;"></i>
                <span style="color: var(--text-secondary); font-size: 0.875rem;"><?= $stats['total_cars'] ?? 0 ?> total in fleet</span>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid var(--secondary);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 8px;">Total Customers</div>
                    <div style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);"><?= $stats['total_customers'] ?? 0 ?></div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(100, 116, 139, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: var(--secondary); font-size: 20px;"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 4px; margin-top: 12px;">
                <?php 
                $customersChange = $stats['customers_change'] ?? 0;
                $customersIcon = $customersChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                $customersColor = $customersChange >= 0 ? 'var(--success)' : 'var(--error)';
                ?>
                <i class="fas <?= $customersIcon ?>" style="color: <?= $customersColor ?>; font-size: 12px;"></i>
                <span style="color: <?= $customersColor ?>; font-size: 0.875rem; font-weight: 600;"><?= number_format(abs($customersChange), 1) ?>%</span>
                <span style="color: var(--text-secondary); font-size: 0.875rem;">new this month</span>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 32px; margin-bottom: 40px;">
        <!-- Revenue Chart -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary);">Revenue Overview</h3>
                <select id="revenuePeriod" class="form-control" style="width: auto; padding: 8px 16px;">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                </select>
            </div>
            <div id="revenueChart" style="height: 300px; background: var(--background); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Recent Activity</h3>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php if (!empty($recent_activity)): ?>
                    <?php foreach ($recent_activity as $activity): ?>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: <?= $activity['bg_color'] ?>; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas <?= $activity['icon'] ?>" style="color: <?= $activity['color'] ?>; font-size: 16px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: var(--text-primary);"><?= $activity['title'] ?></div>
                                <div style="color: var(--text-secondary); font-size: 0.875rem;"><?= $activity['description'] ?></div>
                            </div>
                            <div style="margin-left: auto; color: var(--text-secondary); font-size: 0.875rem;"><?= $activity['time_ago'] ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                        <i class="fas fa-history" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p>No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Quick Actions</h3>
        <div class="grid grid-4">
            <a href="<?= base_url('/admin/addCar') ?>" class="card text-center" style="padding: 24px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 64px; height: 64px; background: rgba(37, 99, 235, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-plus" style="color: var(--primary); font-size: 24px;"></i>
                </div>
                <div style="font-weight: 600; color: var(--text-primary);">Add New Car</div>
            </a>

            <a href="<?= base_url('/admin/manageReservations') ?>" class="card text-center" style="padding: 24px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 64px; height: 64px; background: rgba(16, 185, 129, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-calendar-alt" style="color: var(--success); font-size: 24px;"></i>
                </div>
                <div style="font-weight: 600; color: var(--text-primary);">Manage Reservations</div>
            </a>

            <a href="<?= base_url('/admin/manageCustomers') ?>" class="card text-center" style="padding: 24px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 64px; height: 64px; background: rgba(245, 158, 11, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-users" style="color: var(--warning); font-size: 24px;"></i>
                </div>
                <div style="font-weight: 600; color: var(--text-primary);">View Customers</div>
            </a>

            <a href="<?= base_url('/admin/reports') ?>" class="card text-center" style="padding: 24px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 64px; height: 64px; background: rgba(139, 92, 246, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-chart-bar" style="color: #8b5cf6; font-size: 24px;"></i>
                </div>
                <div style="font-weight: 600; color: var(--text-primary);">View Reports</div>
            </a>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenuePeriod = document.getElementById('revenuePeriod');
    const revenueChartElement = document.getElementById('revenueChart');
    let revenueChart = null;
    
    // Load revenue data when page loads
    loadRevenueData(30);
    
    // Update chart when period changes
    revenuePeriod.addEventListener('change', function() {
        loadRevenueData(this.value);
    });
    
    function loadRevenueData(days) {
        // Show loading indicator
        revenueChartElement.innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';
        
        // Fetch revenue data from server
        fetch(`/admin/getRevenueData?days=${days}`)
            .then(response => response.json())
            .then(data => {
                renderRevenueChart(data);
            })
            .catch(error => {
                console.error('Error fetching revenue data:', error);
                revenueChartElement.innerHTML = '<p style="color: var(--text-secondary);">Error loading revenue data</p>';
            });
    }
    
    function renderRevenueChart(data) {
        // Clear previous chart
        if (revenueChart) {
            revenueChart.destroy();
        }
        
        // Create new chart
        const ctx = document.createElement('canvas');
        revenueChartElement.innerHTML = '';
        revenueChartElement.appendChild(ctx);
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: data.values,
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
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
                                return `₱${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
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
    }
});
</script>

<style>
.grid {
    display: grid;
    gap: 24px;
}

.grid-4 {
    grid-template-columns: repeat(4, 1fr);
}

.card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-color);
}

.text-center {
    text-align: center;
}

.form-control {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 0.875rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
    color: var(--primary);
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .grid[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .grid-4 {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>