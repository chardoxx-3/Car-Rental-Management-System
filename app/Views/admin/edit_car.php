<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<?= $this->include('templates/sidebar') ?>
<div style="margin-left: 280px; padding: 40px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <nav style="margin-bottom: 16px;">
            <a href="<?= base_url('/admin/manageCars') ?>" style="color: var(--text-secondary); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Cars
            </a>
        </nav>
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Edit Car</h1>
        <p style="color: var(--text-secondary);">Update vehicle information and settings</p>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 40px;">
        <!-- Left Column - Form -->
        <div class="card">
            <form method="POST" action="<?= base_url('/admin/updateCar/' . $car['id']) ?>" enctype="multipart/form-data">
                <!-- Current Image -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Current Image</h3>
                    <div style="display: flex; gap: 24px; align-items: flex-start;">
                        <div style="width: 200px; height: 150px; background: var(--background); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <?php if (!empty($car['image'])): ?>
                                <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" 
                                     alt="<?= $car['brand'] . ' ' . $car['model'] ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-car" style="font-size: 48px; color: var(--text-secondary);"></i>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1;">
                            <div class="form-group">
                                <label class="form-label">Update Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <div style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 4px;">Leave empty to keep current image</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h3>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Brand *</label>
                            <input type="text" class="form-control" name="brand" value="<?= old('brand') ?? $car['brand'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Model *</label>
                            <input type="text" class="form-control" name="model" value="<?= old('model') ?? $car['model'] ?>" required>
                        </div>
                    </div>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Year *</label>
                            <input type="number" class="form-control" name="year" value="<?= old('year') ?? $car['year'] ?>" min="2000" max="2030" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Color *</label>
                            <input type="text" class="form-control" name="color" value="<?= old('color') ?? $car['color'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Plate Number *</label>
                        <input type="text" class="form-control" name="plate_number" value="<?= old('plate_number') ?? $car['plate_number'] ?>" required>
                    </div>
                </div>

                <!-- Specifications -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <i class="fas fa-cog"></i> Specifications
                    </h3>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Seating Capacity *</label>
                            <select class="form-control" name="capacity" required>
                                <option value="">Select capacity</option>
                                <option value="2" <?= ($car['capacity'] == 2) ? 'selected' : '' ?>>2 seats</option>
                                <option value="4" <?= ($car['capacity'] == 4) ? 'selected' : '' ?>>4 seats</option>
                                <option value="5" <?= ($car['capacity'] == 5) ? 'selected' : '' ?>>5 seats</option>
                                <option value="7" <?= ($car['capacity'] == 7) ? 'selected' : '' ?>>7 seats</option>
                                <option value="8" <?= ($car['capacity'] == 8) ? 'selected' : '' ?>>8 seats</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Transmission *</label>
                            <select class="form-control" name="transmission" required>
                                <option value="">Select transmission</option>
                                <option value="automatic" <?= ($car['transmission'] == 'automatic') ? 'selected' : '' ?>>Automatic</option>
                                <option value="manual" <?= ($car['transmission'] == 'manual') ? 'selected' : '' ?>>Manual</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Status -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <i class="fas fa-tag"></i> Pricing & Status
                    </h3>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Daily Rate (₱) *</label>
                            <input type="number" class="form-control" name="daily_rate" value="<?= old('daily_rate') ?? $car['daily_rate'] ?>" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-control" name="status" required>
                                <option value="">Select status</option>
                                <option value="available" <?= ($car['status'] == 'available') ? 'selected' : '' ?>>Available</option>
                                <option value="unavailable" <?= ($car['status'] == 'unavailable') ? 'selected' : '' ?>>Unavailable</option>
                                <option value="maintenance" <?= ($car['status'] == 'maintenance') ? 'selected' : '' ?>>Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Enter car description..."><?= old('description') ?? $car['description'] ?></textarea>
                </div>

                <div style="display: flex; gap: 16px; margin-top: 32px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Car
                    </button>
                    <a href="<?= base_url('/admin/manageCars') ?>" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

<!-- Right Column - Car Stats -->
<div>
    <div class="card">
        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 24px;">Car Statistics</h3>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="text-align: center; padding: 20px; background: var(--background); border-radius: 8px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?= $carStats['total_reservations'] ?? 0 ?></div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Total Rentals</div>
            </div>
            <div style="text-align: center; padding: 20px; background: var(--background); border-radius: 8px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success);">₱<?= number_format($carStats['total_revenue'] ?? 0, 2) ?></div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Total Revenue</div>
            </div>
            <div style="text-align: center; padding: 20px; background: var(--background); border-radius: 8px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning);"><?= $carStats['utilization_rate'] ?? 0 ?>%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Utilization Rate</div>
            </div>
        </div>
    </div>

            <!-- Quick Actions -->
            <div class="card" style="margin-top: 24px;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="<?= base_url('/admin/deleteCar/' . $car['id']) ?>" 
                       class="btn btn-outline" 
                       style="justify-content: start; color: var(--error); border-color: var(--error);"
                       onclick="return confirm('Are you sure you want to delete this car? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Car
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>