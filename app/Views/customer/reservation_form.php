<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container" style="padding: 40px 20px;">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 24px;">
        <a href="<?= base_url('/customer/dashboard') ?>" style="color: var(--text-secondary); text-decoration: none;">Home</a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <a href="<?= base_url('/cars') ?>" style="color: var(--text-secondary); text-decoration: none;">Cars</a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <a href="<?= base_url('/customer/carDetails/' . $car['id']) ?>" style="color: var(--text-secondary); text-decoration: none;"><?= $car['brand'] ?> <?= $car['model'] ?></a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <span style="color: var(--text-primary); font-weight: 500;">Book Now</span>
    </nav>

    <div class="grid" style="grid-template-columns: 1fr 400px; gap: 40px;">
        <!-- Left Column - Reservation Form -->
        <div>
            <div class="card">
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Book Your Rental</h1>
                <p style="color: var(--text-secondary); margin-bottom: 32px;">Complete the form below to reserve your vehicle</p>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('/customer/processReservation') ?>">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                    <!-- Rental Period -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-calendar-alt"></i> Rental Period
                        </h3>
                        <div class="grid grid-2" style="gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Pick-up Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" required>
                            </div>
                        </div>
                        <div id="dateError" style="color: var(--error); font-size: 14px; margin-top: 8px; display: none;"></div>
                    </div>

                    <!-- Contact Information -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-user"></i> Contact Information
                        </h3>
                        <div class="grid grid-2" style="gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="<?= session()->get('name') ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?= session()->get('email') ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" value="<?= old('phone') ?>" required>
                        </div>
                    </div>

                    <!-- Additional Services -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-concierge-bell"></i> Additional Services
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer;">
                                <input type="checkbox" name="additional_driver" value="1" style="width: 18px; height: 18px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--text-primary);">Additional Driver</div>
                                    <div style="color: var(--text-secondary); font-size: 14px;">Add an extra driver to your rental</div>
                                </div>
                                <span style="font-weight: 600; color: var(--primary);">+₱15/day</span>
                            </label>
                            
                            <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer;">
                                <input type="checkbox" name="child_seat" value="1" style="width: 18px; height: 18px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--text-primary);">Child Safety Seat</div>
                                    <div style="color: var(--text-secondary); font-size: 14px;">Perfect for traveling with children</div>
                                </div>
                                <span style="font-weight: 600; color: var(--primary);">+₱10/day</span>
                            </label>
                            
                            <label style="display: flex; align-items: center; gap: 12px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer;">
                                <input type="checkbox" name="gps" value="1" style="width: 18px; height: 18px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: var(--text-primary);">GPS Navigation</div>
                                    <div style="color: var(--text-secondary); font-size: 14px;">Never get lost on your journey</div>
                                </div>
                                <span style="font-weight: 600; color: var(--primary);">+₱8/day</span>
                            </label>
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div class="form-group">
                        <label class="form-label">Special Requests (Optional)</label>
                        <textarea class="form-control" name="special_requests" rows="4" placeholder="Any special requirements or requests..."><?= old('special_requests') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 24px;">
                        <i class="fas fa-calendar-check"></i> Continue to Payment
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Booking Summary -->
        <div style="position: sticky; top: 100px; align-self: start;">
            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Booking Summary</h3>
                
                <!-- Car Info -->
                <div style="display: flex; gap: 16px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
                    <div style="width: 80px; height: 60px; background: var(--background); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <?php if (!empty($car['image'])): ?>
                            <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" 
                                 alt="<?= $car['brand'] . ' ' . $car['model'] ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                        <?php else: ?>
                            <i class="fas fa-car" style="color: var(--text-secondary);"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;"><?= $car['brand'] ?> <?= $car['model'] ?></div>
                        <div style="color: var(--text-secondary); font-size: 14px;"><?= $car['year'] ?> • <?= ucfirst($car['transmission']) ?></div>
                    </div>
                </div>

<!-- Price Breakdown -->
<div style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: between; margin-bottom: 12px;">
        <span style="color: var(--text-secondary);">Daily rate</span>
        <span style="font-weight: 600;">₱<?= number_format($car['daily_rate']) ?>/day</span>
    </div>
    <div style="display: flex; justify-content: between; margin-bottom: 12px;">
        <span style="color: var(--text-secondary);">Rental days</span>
        <span style="font-weight: 600;" id="rentalDays">0 days</span>
    </div>
    <div style="display: flex; justify-content: between; margin-bottom: 12px;">
        <span style="color: var(--text-secondary);">Additional services</span>
        <span style="font-weight: 600;" id="additionalServices">₱0</span>
    </div>
</div>

                <!-- Total -->
                <div style="padding-top: 16px; border-top: 2px solid var(--border);">
                    <div style="display: flex; justify-content: between; align-items: center;">
                        <span style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary);">Total</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);" id="totalAmount">₱0</span>
                    </div>
                    <div style="color: var(--text-secondary); font-size: 14px; margin-top: 4px;" id="taxNote">Including all taxes and fees</div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="card" style="margin-top: 20px;">
                <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Important Information</h4>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fas fa-info-circle" style="color: var(--primary); margin-top: 2px;"></i>
                        <span style="color: var(--text-secondary); font-size: 14px;">Free cancellation up to 24 hours before pickup</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fas fa-id-card" style="color: var(--primary); margin-top: 2px;"></i>
                        <span style="color: var(--text-secondary); font-size: 14px;">Valid driver's license and credit card required</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <i class="fas fa-gas-pump" style="color: var(--primary); margin-top: 2px;"></i>
                        <span style="color: var(--text-secondary); font-size: 14px;">Vehicle must be returned with same fuel level</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const dateError = document.getElementById('dateError');
    const rentalDays = document.getElementById('rentalDays');
    const additionalServices = document.getElementById('additionalServices');
    const totalAmount = document.getElementById('totalAmount');
    
    const dailyRate = <?= $car['daily_rate'] ?>;
    
    function calculateTotal() {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        
        // Reset error
        dateError.style.display = 'none';
        
        if (startDate.value && endDate.value) {
            if (end <= start) {
                dateError.textContent = 'Return date must be after pick-up date';
                dateError.style.display = 'block';
                rentalDays.textContent = '0 days';
                additionalServices.textContent = '₱0';
                totalAmount.textContent = '₱0';
                return;
            }
            
            // Calculate days
            const timeDiff = end.getTime() - start.getTime();
            const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            if (days < 1) {
                dateError.textContent = 'Minimum rental period is 1 day';
                dateError.style.display = 'block';
                rentalDays.textContent = '0 days';
                additionalServices.textContent = '₱0';
                totalAmount.textContent = '₱0';
                return;
            }
            
            // Calculate additional services
            let additionalCost = 0;
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const priceText = checkbox.parentElement.querySelector('span').textContent;
                    // Extract price from text like "+₱15/day"
                    const price = parseInt(priceText.match(/₱(\d+)/)[1]);
                    additionalCost += price * days;
                }
            });
            
            // Calculate totals
            const baseCost = dailyRate * days;
            const total = baseCost + additionalCost;
            
            // Update display with Philippine Peso symbol
            rentalDays.textContent = days + ' day' + (days !== 1 ? 's' : '');
            additionalServices.textContent = additionalCost > 0 ? '₱' + additionalCost.toLocaleString() : '₱0';
            totalAmount.textContent = '₱' + total.toLocaleString();
        } else {
            // Reset if dates not selected
            rentalDays.textContent = '0 days';
            additionalServices.textContent = '₱0';
            totalAmount.textContent = '₱0';
        }
    }
    
    // Event listeners
    startDate.addEventListener('change', calculateTotal);
    endDate.addEventListener('change', calculateTotal);
    
    // Add event listeners to checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });
    
    // Set minimum end date based on start date
    startDate.addEventListener('change', function() {
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayFormatted = nextDay.toISOString().split('T')[0];
            endDate.min = nextDayFormatted;
            
            if (endDate.value && endDate.value < this.value) {
                endDate.value = '';
                calculateTotal();
            }
        }
    });
    
    // Initialize calculation if dates are pre-filled (for form re-submission)
    if (startDate.value && endDate.value) {
        calculateTotal();
    }
});
</script>

<style>
@media (max-width: 1024px) {
    .container > .grid {
        grid-template-columns: 1fr;
    }
    
    .container > .grid > div:last-child {
        position: static;
    }
}

input[type="checkbox"] {
    accent-color: var(--primary);
}
</style>
<?= $this->endSection() ?>