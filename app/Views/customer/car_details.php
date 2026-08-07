<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container" style="padding: 40px 20px;">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 24px;">
        <a href="<?= base_url('/customer/dashboard') ?>" style="color: var(--text-secondary); text-decoration: none;">Home</a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <a href="<?= base_url('/cars') ?>" style="color: var(--text-secondary); text-decoration: none;">Cars</a>
        <span style="color: var(--text-secondary); margin: 0 8px;">/</span>
        <span style="color: var(--text-primary); font-weight: 500;"><?= $car['brand'] ?> <?= $car['model'] ?></span>
    </nav>

    <div class="grid" style="grid-template-columns: 1fr 400px; gap: 40px;">
        <!-- Left Column - Car Details -->
        <div>
            <!-- Car Images -->
            <div class="card" style="margin-bottom: 24px;">
                <div style="width: 100%; height: 400px; background: var(--background); border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($car['image'])): ?>
                        <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" 
                             alt="<?= $car['brand'] . ' ' . $car['model'] ?>" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-car" style="font-size: 120px; color: var(--text-secondary);"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Car Information -->
            <div class="card">
                <h2 style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 24px;">Vehicle Details</h2>
                
                <div class="grid grid-2" style="gap: 20px; margin-bottom: 32px;">
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Brand</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['brand'] ?></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-car-side" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Model</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['model'] ?></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Capacity</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['capacity'] ?> seats</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-cog" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Transmission</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= ucfirst($car['transmission']) ?></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-palette" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Color</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['color'] ?></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--background); border-radius: 8px;">
                        <div style="width: 48px; height: 48px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-secondary);">Year</div>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= $car['year'] ?></div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <?php if (!empty($car['description'])): ?>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Description</h3>
                        <p style="color: var(--text-secondary); line-height: 1.6;"><?= $car['description'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column - Booking Panel -->
        <div style="position: sticky; top: 100px; align-self: start;">
            <div class="card">
                <!-- Price -->
                <div style="text-align: center; margin-bottom: 24px;">
                    <div style="font-size: 3rem; font-weight: 700; color: var(--primary); line-height: 1;">₱<?= $car['daily_rate'] ?></div>
                    <div style="color: var(--text-secondary);">per day</div>
                </div>

                <!-- Status -->
                <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 24px;">
                    <div style="width: 8px; height: 8px; background: var(--success); border-radius: 50%;"></div>
                    <span style="color: var(--success); font-weight: 600;">Available for Rent</span>
                </div>

                <!-- Quick Actions -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="<?= base_url('/customer/makeReservation/' . $car['id']) ?>" class="btn btn-primary" style="justify-content: center;">
                        <i class="fas fa-calendar-plus"></i> Book This Car
                    </a>
                </div>

                <!-- Features List -->
                <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Included Features</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-check" style="color: var(--success);"></i>
                            <span style="color: var(--text-secondary);">Free Cancellation</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-check" style="color: var(--success);"></i>
                            <span style="color: var(--text-secondary);">Comprehensive Insurance</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-check" style="color: var(--success);"></i>
                            <span style="color: var(--text-secondary);">24/7 Roadside Assistance</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-check" style="color: var(--success);"></i>
                            <span style="color: var(--text-secondary);">Unlimited Mileage</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .container > .grid {
        grid-template-columns: 1fr;
    }
    
    .container > .grid > div:last-child {
        position: static;
    }
}
</style>
<?= $this->endSection() ?>