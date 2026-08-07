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
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">Add New Car</h1>
        <p style="color: var(--text-secondary);">Add a new vehicle to your rental fleet</p>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 40px;">
        <!-- Left Column - Form -->
        <div class="card">
            <form method="POST" action="<?= base_url('/admin/storeCar') ?>" enctype="multipart/form-data">
                <!-- Basic Information -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h3>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Brand *</label>
                            <input type="text" class="form-control" name="brand" value="<?= old('brand') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Model *</label>
                            <input type="text" class="form-control" name="model" value="<?= old('model') ?>" required>
                        </div>
                    </div>
                    <div class="grid grid-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Year *</label>
                            <input type="number" class="form-control" name="year" value="<?= old('year') ?>" min="2000" max="2030" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Color *</label>
                            <input type="text" class="form-control" name="color" value="<?= old('color') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Plate Number *</label>
                        <input type="text" class="form-control" name="plate_number" value="<?= old('plate_number') ?>" required>
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
                                <option value="2">2 seats</option>
                                <option value="4">4 seats</option>
                                <option value="5">5 seats</option>
                                <option value="7">7 seats</option>
                                <option value="8">8 seats</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Transmission *</label>
                            <select class="form-control" name="transmission" required>
                                <option value="">Select transmission</option>
                                <option value="automatic">Automatic</option>
                                <option value="manual">Manual</option>
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
                            <input type="number" class="form-control" name="daily_rate" value="<?= old('daily_rate') ?>" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-control" name="status" required>
                                <option value="">Select status</option>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                        <i class="fas fa-image"></i> Car Images
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Main Image</label>
                        <div style="border: 2px dashed var(--border); border-radius: 8px; padding: 40px; text-align: center; cursor: pointer;" id="imageUploadArea">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 16px;"></i>
                            <div style="color: var(--text-primary); font-weight: 600; margin-bottom: 8px;">Click to upload image</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">PNG, JPG up to 5MB</div>
                            <input type="file" name="image" accept="image/*" style="display: none;" id="imageInput">
                        </div>
                        <div id="imagePreview" style="margin-top: 16px; display: none;">
                            <img id="previewImage" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid var(--border);">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Enter car description..."><?= old('description') ?></textarea>
                </div>

                <div style="display: flex; gap: 16px; margin-top: 32px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Car
                    </button>
                    <a href="<?= base_url('/admin/manageCars') ?>" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Right Column - Guidelines -->
        <div>
            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Guidelines</h3>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-camera" style="color: white; font-size: 12px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">High-Quality Images</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Use clear, well-lit photos from multiple angles</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-tag" style="color: white; font-size: 12px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Competitive Pricing</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Research similar vehicles in your area for optimal pricing</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-clipboard-check" style="color: white; font-size: 12px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Accurate Information</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Ensure all vehicle details are correct and up-to-date</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-bell" style="color: white; font-size: 12px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Status Management</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Update vehicle status promptly for accurate availability</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card" style="margin-top: 24px;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 16px;">Quick Tips</h3>
                <ul style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6; padding-left: 20px;">
                    <li>Include all major features in the description</li>
                    <li>Set maintenance status for vehicles under service</li>
                    <li>Use descriptive text to highlight unique features</li>
                    <li>Regularly update pricing based on market trends</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    imageUploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.style.display = 'block';
                imageUploadArea.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop functionality
    imageUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        imageUploadArea.style.borderColor = 'var(--primary)';
        imageUploadArea.style.background = 'rgba(37, 99, 235, 0.05)';
    });
    
    imageUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        imageUploadArea.style.borderColor = 'var(--border)';
        imageUploadArea.style.background = 'transparent';
    });
    
    imageUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        imageUploadArea.style.borderColor = 'var(--border)';
        imageUploadArea.style.background = 'transparent';
        
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            imageInput.files = e.dataTransfer.files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    });
});
</script>

<style>
@media (max-width: 1024px) {
    .container > div {
        margin-left: 0 !important;
    }
    
    .grid[style*="grid-template-columns: 2fr 1fr"] {
        grid-template-columns: 1fr;
    }
}

#imageUploadArea {
    transition: all 0.3s ease;
}

#imageUploadArea:hover {
    border-color: var(--primary);
    background: rgba(37, 99, 235, 0.05);
}
</style>
<?= $this->endSection() ?>