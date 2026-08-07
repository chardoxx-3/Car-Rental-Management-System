<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>

<!-- Main Layout Container -->
<div class="dashboard-container">
    
    <!-- 1. Hero / Welcome Section -->
    <div class="hero-card">
        <div class="hero-content">
            <div>
                <h1>Welcome back, <?= session()->get('name') ?>!</h1>
                <p>Ready for your next journey? Browse our premium fleet or manage your current bookings.</p>
            </div>
            <!-- Added a Call to Action for better UX -->
            <a href="<?= base_url('/cars') ?>" class="btn-hero">
                Find a Car <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <!-- Decorative Circle for visual depth -->
        <div class="hero-decoration"></div>
    </div>

    <!-- 2. Quick Stats Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon-box primary-light">
                <i class="fas fa-car"></i>
            </div>
            <div class="stat-info">
                <h3><?= count($cars) ?></h3>
                <p>Available Cars</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon-box success-light">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3>0</h3> <!-- Dynamic variable here if needed -->
                <p>Active Bookings</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="icon-box warning-light">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-info">
                <h3>0</h3> <!-- Dynamic variable here if needed -->
                <p>Total Rentals</p>
            </div>
        </div>
    </div>

    <!-- 3. Featured Vehicles Section -->
    <section class="section-block">
        <div class="section-header">
            <div>
                <h2>Featured Vehicles</h2>
                <p class="subtitle">Top rated cars for your comfort and style</p>
            </div>
            <a href="<?= base_url('/cars') ?>" class="link-action">
                View All Cars <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <?php if (empty($cars)): ?>
            <!-- Improved Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-car-side"></i>
                </div>
                <h3>No Vehicles Available</h3>
                <p>We are currently updating our fleet. Please check back soon!</p>
            </div>
        <?php else: ?>
            <div class="cars-grid">
                <?php foreach (array_slice($cars, 0, 6) as $car): ?>
                    <div class="car-card" onclick="window.location.href='<?= base_url('/customer/carDetails/' . $car['id']) ?>'">
                        
                        <!-- Image Area -->
                        <div class="car-image-wrapper">
                            <div class="badge-available">Available</div>
                            <?php if (!empty($car['image'])): ?>
                                <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                            <?php else: ?>
                                <div class="placeholder-image">
                                    <i class="fas fa-car"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content Area -->
                        <div class="car-content">
                            <div class="car-header">
                                <h3><?= $car['brand'] ?> <?= $car['model'] ?></h3>
                                <div class="car-type"><?= ucfirst($car['transmission']) ?></div>
                            </div>
                            
                            <div class="car-specs">
                                <span><i class="fas fa-users"></i> <?= $car['capacity'] ?> Seats</span>
                                <span><i class="fas fa-gas-pump"></i> Fuel</span> <!-- Static for now, can be dynamic -->
                            </div>

                            <div class="car-footer">
                                <div class="price-block">
                                    <span class="currency">₱</span>
                                    <span class="amount"><?= $car['daily_rate'] ?></span>
                                    <span class="period">/day</span>
                                </div>
                                <button class="btn-icon">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- 4. How It Works (Visual Process) -->
    <section class="section-block how-it-works">
        <h2 class="text-center">Simple & Fast Booking</h2>
        <div class="steps-container">
            <!-- Connecting Line -->
            <div class="step-line"></div>
            
            <!-- Step 1 -->
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Browse</h3>
                <p>Select from our premium fleet.</p>
            </div>
            
            <!-- Step 2 -->
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Book</h3>
                <p>Choose dates & secure payment.</p>
            </div>
            
            <!-- Step 3 -->
            <div class="step-item">
                <div class="step-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h3>Drive</h3>
                <p>Pick up keys & enjoy the ride.</p>
            </div>
        </div>
    </section>

</div>

<style>
    /* --- Base Variables & Layout --- */
    :root {
        --card-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* --- Hero Section --- */
    .hero-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--card-radius);
        padding: 40px;
        color: white;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px var(--primary);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        align-items: flex-start;
    }

    .hero-content h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .hero-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .btn-hero {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .btn-hero:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-2px);
    }

    /* Abstract Circle Decoration */
    .hero-decoration {
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    /* --- Stats Grid --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
    }

    .stat-card {
        background: var(--surface);
        padding: 24px;
        border-radius: var(--card-radius);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .primary-light { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
    .success-light { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .warning-light { background: rgba(245, 158, 11, 0.1); color: var(--warning); }

    .stat-info h3 { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0; line-height: 1; }
    .stat-info p { color: var(--text-secondary); margin: 4px 0 0 0; font-size: 0.9rem; font-weight: 500; }

    /* --- Featured Vehicles --- */
    .section-block { margin-bottom: 60px; }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 32px;
    }

    .section-header h2 { font-size: 1.8rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .subtitle { color: var(--text-secondary); margin: 8px 0 0 0; }
    
    .link-action {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s;
    }
    .link-action:hover { gap: 10px; }

    .cars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .car-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--card-radius);
        overflow: hidden;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .car-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .car-image-wrapper {
        height: 200px;
        background: var(--background);
        position: relative;
        overflow: hidden;
    }

    .car-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .car-card:hover img { transform: scale(1.05); }

    .badge-available {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(16, 185, 129, 0.9);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
        z-index: 2;
    }

    .placeholder-image {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-secondary);
        font-size: 48px;
    }

    .car-content { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }

    .car-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 16px;
    }

    .car-header h3 { margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-primary); }
    .car-type {
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;
        color: var(--text-secondary); font-weight: 600;
        background: var(--background); padding: 4px 8px; border-radius: 4px;
    }

    .car-specs {
        display: flex; gap: 16px; margin-bottom: 20px;
        color: var(--text-secondary); font-size: 0.9rem;
    }
    .car-specs i { color: var(--primary); margin-right: 6px; }

    .car-footer {
        margin-top: auto;
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 16px; border-top: 1px solid var(--border);
    }

    .price-block .currency { font-size: 1rem; font-weight: 600; color: var(--primary); vertical-align: top; }
    .price-block .amount { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }
    .price-block .period { font-size: 0.9rem; color: var(--text-secondary); }

    .btn-icon {
        width: 36px; height: 36px;
        border-radius: 50%; border: 1px solid var(--border);
        background: transparent; color: var(--primary);
        cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center;
    }
    .car-card:hover .btn-icon { background: var(--primary); color: white; border-color: var(--primary); }

    /* --- Empty State --- */
    .empty-state {
        text-align: center; padding: 60px 20px;
        background: var(--surface); border-radius: var(--card-radius);
        border: 2px dashed var(--border);
    }
    .empty-icon {
        font-size: 48px; color: var(--text-secondary); opacity: 0.5;
        margin-bottom: 16px;
    }

    /* --- How It Works --- */
    .how-it-works h2 { margin-bottom: 48px; font-weight: 800; color: var(--text-primary); text-align: center; }
    
    .steps-container {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 32px; position: relative; max-width: 900px; margin: 0 auto;
    }

    /* Dashed Line Background */
    .step-line {
        position: absolute; top: 40px; left: 15%; right: 15%; height: 2px;
        background-image: linear-gradient(to right, var(--border) 50%, transparent 50%);
        background-size: 20px 1px; background-repeat: repeat-x; z-index: 0;
    }

    .step-item { position: relative; z-index: 1; text-align: center; }
    
    .step-icon {
        width: 80px; height: 80px;
        background: var(--surface); border: 2px solid var(--primary);
        color: var(--primary); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; margin: 0 auto 20px;
        box-shadow: 0 0 0 8px var(--background); /* Creates a gap in the line */
    }

    .step-item h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; color: var(--text-primary); }
    .step-item p { color: var(--text-secondary); font-size: 0.95rem; }

    /* --- Mobile & Tablet Response --- */
    @media (max-width: 992px) {
        .steps-container { grid-template-columns: 1fr; gap: 40px; }
        .step-line { display: none; }
        .step-icon { width: 64px; height: 64px; font-size: 24px; box-shadow: none; margin-bottom: 12px; }
    }

    @media (max-width: 768px) {
        .hero-card { flex-direction: column; text-align: center; padding: 32px 20px; }
        .hero-content { align-items: center; }
        .hero-decoration { display: none; }
        .stats-grid { grid-template-columns: 1fr; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .dashboard-container { padding: 20px 16px; }
    }
</style>
<?= $this->endSection() ?>