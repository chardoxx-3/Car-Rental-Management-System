<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container" style="padding: 40px 20px;">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 24px;">
        <a href="<?= base_url('/customer/dashboard') ?>" style="color: var(--text-secondary); text-decoration: none;">Home</a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <a href="<?= base_url('/customer/carDetails/' . $car['id']) ?>" style="color: var(--text-secondary); text-decoration: none;"><?= $car['brand'] ?> <?= $car['model'] ?></a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <span style="color: var(--text-primary); font-weight: 500;">Payment</span>
    </nav>

    <div class="grid" style="grid-template-columns: 1fr 400px; gap: 40px;">
        <!-- Left Column - Payment Form -->
        <div>
            <div class="card">
                <!-- Progress Steps -->
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 40px;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            1
                        </div>
                        <div style="width: 80px; height: 2px; background: var(--primary);"></div>
                        <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            2
                        </div>
                        <div style="width: 80px; height: 2px; background: var(--primary);"></div>
                        <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            3
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: between; margin-bottom: 40px; text-align: center;">
                    <div style="flex: 1; font-size: 14px; color: var(--text-secondary);">Details</div>
                    <div style="flex: 1; font-size: 14px; color: var(--text-secondary);">Reservation</div>
                    <div style="flex: 1; font-size: 14px; color: var(--primary); font-weight: 600;">Payment</div>
                </div>

                <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Payment Method</h1>
                <p style="color: var(--text-secondary); margin-bottom: 32px;">Choose your preferred payment method</p>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('/customer/processPayment') ?>">
                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">

                    <!-- Payment Method Selection -->
                    <div style="margin-bottom: 32px;">
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px;">
                            <i class="fas fa-credit-card"></i> Select Payment Method
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 24px;">
                            <label style="border: 2px solid var(--border); border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                                <input type="radio" name="payment_method" value="credit_card" style="display: none;">
                                <i class="fab fa-cc-visa" style="font-size: 32px; color: var(--text-secondary); margin-bottom: 8px; display: block;"></i>
                                <div style="font-weight: 600; color: var(--text-primary);">Credit Card</div>
                            </label>
                            
                            <label style="border: 2px solid var(--border); border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                                <input type="radio" name="payment_method" value="debit_card" style="display: none;">
                                <i class="fas fa-credit-card" style="font-size: 32px; color: var(--text-secondary); margin-bottom: 8px; display: block;"></i>
                                <div style="font-weight: 600; color: var(--text-primary);">Debit Card</div>
                            </label>

                            
                            <label style="border: 2px solid var(--border); border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                                <input type="radio" name="payment_method" value="cash" style="display: none;">
                                <i class="fas fa-money-bill-wave" style="font-size: 32px; color: var(--text-secondary); margin-bottom: 8px; display: block;"></i>
                                <div style="font-weight: 600; color: var(--text-primary);">Cash</div>
                            </label>
                        </div>
                    </div>

                    <!-- Credit Card Form (shown when credit/debit card selected) -->
                    <div id="cardForm" style="display: none;">
                        <div class="grid grid-2" style="gap: 16px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" class="form-control" name="card_name" placeholder="John Doe">
                            </div>
                        </div>
                        
                        <div class="grid grid-3" style="gap: 16px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Expiry Month</label>
                                <select class="form-control" name="expiry_month">
                                    <option value="">Month</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= sprintf('%02d', $i) ?>"><?= sprintf('%02d', $i) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Expiry Year</label>
                                <select class="form-control" name="expiry_year">
                                    <option value="">Year</option>
                                    <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" name="cvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                    </div>

                    <!-- PayPal Info (shown when PayPal selected) -->
                    <div id="paypalInfo" style="display: none; padding: 20px; background: var(--background); border-radius: 8px; margin-bottom: 20px;">
                        <p style="color: var(--text-secondary); text-align: center;">
                            You will be redirected to PayPal to complete your payment after submitting this form.
                        </p>
                    </div>

                    <!-- Cash Info (shown when Cash selected) -->
                    <div id="cashInfo" style="display: none; padding: 20px; background: var(--background); border-radius: 8px; margin-bottom: 20px;">
                        <p style="color: var(--text-secondary); text-align: center;">
                            Please bring exact cash when you pick up the vehicle. A deposit may be required.
                        </p>
                    </div>

                    <!-- Terms and Conditions -->
                    <div style="margin-bottom: 32px;">
                        <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="terms" required style="margin-top: 4px; width: 18px; height: 18px;">
                            <span style="color: var(--text-secondary); font-size: 14px; line-height: 1.4;">
                                I agree to the <a href="#" style="color: var(--primary); text-decoration: none;">rental terms and conditions</a> and authorize DriveRent to charge my payment method for the total amount.
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 16px;">
                        <i class="fas fa-lock"></i> Complete Payment - ₱<?= $reservation['total_cost'] ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div style="position: sticky; top: 100px; align-self: start;">
            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Order Summary</h3>
                
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

                <!-- Rental Period -->
                <div style="margin-bottom: 20px;">
                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">Rental Period</h4>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: var(--text-secondary);">Pick-up</span>
                        <span style="font-weight: 600;"><?= date('M j, Y', strtotime($reservation['start_date'])) ?></span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: var(--text-secondary);">Return</span>
                        <span style="font-weight: 600;"><?= date('M j, Y', strtotime($reservation['end_date'])) ?></span>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">Price Breakdown</h4>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: var(--text-secondary);">Base rate</span>
                        <span>₱<?= $reservation['total_cost'] ?></span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: var(--text-secondary);">Taxes & fees</span>
                        <span>Included</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: var(--text-secondary);">Insurance</span>
                        <span>Included</span>
                    </div>
                </div>

                <!-- Total -->
                <div style="padding-top: 16px; border-top: 2px solid var(--border);">
                    <div style="display: flex; justify-content: between; align-items: center;">
                        <span style="font-size: 1.125rem; font-weight: 700; color: var(--text-primary);">Total</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">₱<?= $reservation['total_cost'] ?></span>
                    </div>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="card" style="margin-top: 20px; text-align: center;">
                <i class="fas fa-shield-alt" style="font-size: 48px; color: var(--success); margin-bottom: 12px;"></i>
                <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Secure Payment</h4>
                <p style="color: var(--text-secondary); font-size: 14px;">Your payment information is encrypted and secure</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardForm = document.getElementById('cardForm');
    const paypalInfo = document.getElementById('paypalInfo');
    const cashInfo = document.getElementById('cashInfo');
    
    // Style payment method selection
    const paymentLabels = document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        const label = input.parentElement;
        
        input.addEventListener('change', function() {
            // Remove selected style from all labels
            document.querySelectorAll('input[name="payment_method"]').forEach(i => {
                i.parentElement.style.borderColor = 'var(--border)';
                i.parentElement.style.background = 'transparent';
            });
            
            // Add selected style to current label
            if (this.checked) {
                label.style.borderColor = 'var(--primary)';
                label.style.background = 'rgba(37, 99, 235, 0.05)';
            }
            
            // Show/hide appropriate forms
            cardForm.style.display = 'none';
            paypalInfo.style.display = 'none';
            cashInfo.style.display = 'none';
            
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                cardForm.style.display = 'block';
            } else if (this.value === 'paypal') {
                paypalInfo.style.display = 'block';
            } else if (this.value === 'cash') {
                cashInfo.style.display = 'block';
            }
        });
    });
    
    // Format card number
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let matches = value.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];
            
            for (let i = 0; i < match.length; i += 4) {
                parts.push(match.substring(i, i + 4));
            }
            
            if (parts.length) {
                this.value = parts.join(' ');
            }
        });
    }
});
</script>

<style>
input[type="radio"]:checked + div i {
    color: var(--primary) !important;
}

@media (max-width: 1024px) {
    .container > .grid {
        grid-template-columns: 1fr;
    }
    
    .container > .grid > div:last-child {
        position: static;
    }
}

input[type="radio"] {
    accent-color: var(--primary);
}
</style>
<?= $this->endSection() ?>