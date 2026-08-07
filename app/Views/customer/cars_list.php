<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="fleet-container">
    
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Our Premium Fleet</h1>
            <p>Find the perfect vehicle for your journey.</p>
        </div>
        <!-- Optional: Total count indicator -->
        <div class="fleet-count-badge">
            <span id="totalCarsCount"><?= count($cars) ?></span> Vehicles Available
        </div>
    </div>

    <!-- Modern Filter Control Bar -->
    <div class="control-bar">
        <div class="filter-group">
            <div class="input-wrapper">
                <i class="fas fa-car-side input-icon"></i>
                <select id="filterBrand" class="custom-select" onchange="filterCars()">
                    <option value="">All Brands</option>
                    <option value="Toyota">Toyota</option>
                    <option value="Honda">Honda</option>
                    <option value="BMW">BMW</option>
                    <option value="Mercedes">Mercedes</option>
                </select>
            </div>
            
            <div class="input-wrapper">
                <i class="fas fa-cogs input-icon"></i>
                <select id="filterTransmission" class="custom-select" onchange="filterCars()">
                    <option value="">All Transmissions</option>
                    <option value="automatic">Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            
            <div class="input-wrapper">
                <i class="fas fa-tag input-icon"></i>
                <select id="filterPrice" class="custom-select" onchange="filterCars()">
                    <option value="">Any Price</option>
                    <option value="0-50">₱0 - ₱50</option>
                    <option value="50-100">₱50 - ₱100</option>
                    <option value="100-200">₱100 - ₱200</option>
                    <option value="200+">₱200+</option>
                </select>
            </div>
        </div>

        <div class="sort-group">
            <div class="input-wrapper">
                <i class="fas fa-sort-amount-down input-icon"></i>
                <select id="sortBy" class="custom-select" onchange="sortCars()">
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="name_asc">Name: A to Z</option>
                    <option value="name_desc">Name: Z to A</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Cars Grid -->
    <div id="carsContainer" class="cars-grid-wrapper">
        
        <!-- Pre-rendered No Results State (Hidden by default) -->
        <div id="noResultsState" class="empty-state" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No Vehicles Found</h3>
            <p>Try adjusting your filters to find what you're looking for.</p>
            <button onclick="resetFilters()" class="btn-reset">Reset Filters</button>
        </div>

        <?php if (empty($cars)): ?>
            <!-- Database Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-car-crash"></i>
                </div>
                <h3>Fleet Currently Unavailable</h3>
                <p>We are updating our inventory. Please check back later.</p>
                <a href="<?= base_url('/customer/dashboard') ?>" class="btn-reset">
                    <i class="fas fa-arrow-left"></i> Return Home
                </a>
            </div>
        <?php else: ?>
            <div class="grid-layout">
                <?php foreach ($cars as $car): ?>
                    <div class="car-card" 
                         data-brand="<?= strtolower($car['brand']) ?>" 
                         data-transmission="<?= strtolower($car['transmission']) ?>" 
                         data-price="<?= $car['daily_rate'] ?>"
                         data-name="<?= strtolower($car['brand'] . ' ' . $car['model']) ?>"
                         onclick="window.location.href='<?= base_url('/customer/carDetails/' . $car['id']) ?>'">
                        
                        <!-- Image Section -->
                        <div class="card-image">
                            <span class="status-badge available">Available</span>
                            <?php if (!empty($car['image'])): ?>
                                <img src="<?= base_url('/uploads/cars/' . $car['image']) ?>" alt="<?= $car['brand'] . ' ' . $car['model'] ?>">
                            <?php else: ?>
                                <div class="placeholder-img">
                                    <i class="fas fa-car"></i>
                                </div>
                            <?php endif; ?>
                            <!-- Hover Overlay -->
                            <div class="card-overlay">
                                <span>View Details</span>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="card-body">
                            <div class="card-header">
                                <h3 class="car-title"><?= $car['brand'] ?> <?= $car['model'] ?></h3>
                                <span class="car-year"><?= $car['year'] ?></span>
                            </div>
                            
                            <!-- Mini Specs Grid -->
                            <div class="specs-grid">
                                <div class="spec-item" title="Capacity">
                                    <i class="fas fa-users"></i>
                                    <span><?= $car['capacity'] ?></span>
                                </div>
                                <div class="spec-item" title="Transmission">
                                    <i class="fas fa-cog"></i>
                                    <span><?= ucfirst($car['transmission']) ?></span>
                                </div>
                                <div class="spec-item" title="Color">
                                    <i class="fas fa-palette"></i>
                                    <span><?= $car['color'] ?></span>
                                </div>
                                <div class="spec-item" title="Type">
                                    <i class="fas fa-gas-pump"></i>
                                    <span>Gas</span>
                                </div>
                            </div>

                            <div class="card-divider"></div>

                            <!-- Price & Action -->
                            <div class="card-footer">
                                <div class="price-info">
                                    <span class="currency">₱</span>
                                    <span class="amount"><?= $car['daily_rate'] ?></span>
                                    <span class="unit">/day</span>
                                </div>
                                <button class="btn-view">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterCars() {
    // Get values using distinct IDs
    const brandFilter = document.getElementById('filterBrand').value.toLowerCase();
    const transmissionFilter = document.getElementById('filterTransmission').value.toLowerCase(); // Ensure lowercase comparison
    const priceFilter = document.getElementById('filterPrice').value;
    
    const carCards = document.querySelectorAll('.car-card');
    const noResultsState = document.getElementById('noResultsState');
    const gridLayout = document.querySelector('.grid-layout');
    
    let visibleCount = 0;
    
    carCards.forEach(card => {
        const brand = card.getAttribute('data-brand');
        const transmission = card.getAttribute('data-transmission');
        const price = parseFloat(card.getAttribute('data-price'));
        
        let show = true;
        
        // Brand filter
        if (brandFilter && !brand.includes(brandFilter)) {
            show = false;
        }
        
        // Transmission filter
        if (transmissionFilter && transmission !== transmissionFilter) {
            show = false;
        }
        
        // Price filter
        if (priceFilter) {
            const [minStr, maxStr] = priceFilter.split('-');
            const min = parseFloat(minStr);
            const max = maxStr === '+' ? Infinity : parseFloat(maxStr); // Handle "200+" case
            
            if (price < min || price > max) {
                show = false;
            }
        }
        
        if (show) {
            card.style.display = 'flex'; // Restore flex display
            // Optional: Add a fade-in animation class here if desired
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update Total Count
    const countBadge = document.getElementById('totalCarsCount');
    if(countBadge) countBadge.textContent = visibleCount;

    // Toggle Empty State
    if (visibleCount === 0) {
        if(gridLayout) gridLayout.style.display = 'none';
        noResultsState.style.display = 'block';
    } else {
        if(gridLayout) gridLayout.style.display = 'grid';
        noResultsState.style.display = 'none';
    }
}

function sortCars() {
    const sortBy = document.getElementById('sortBy').value;
    const grid = document.querySelector('.grid-layout');
    const carCards = Array.from(document.querySelectorAll('.car-card'));
    
    carCards.sort((a, b) => {
        const priceA = parseFloat(a.getAttribute('data-price'));
        const priceB = parseFloat(b.getAttribute('data-price'));
        const nameA = a.getAttribute('data-name');
        const nameB = b.getAttribute('data-name');

        switch (sortBy) {
            case 'price_asc': return priceA - priceB;
            case 'price_desc': return priceB - priceA;
            case 'name_asc': return nameA.localeCompare(nameB);
            case 'name_desc': return nameB.localeCompare(nameA);
            default: return 0;
        }
    });
    
    // Re-append sorted cards
    carCards.forEach(card => grid.appendChild(card));
}

function resetFilters() {
    document.getElementById('filterBrand').value = "";
    document.getElementById('filterTransmission').value = "";
    document.getElementById('filterPrice').value = "";
    filterCars();
}
</script>

<style>
    :root {
        --card-radius: 12px;
        --input-bg: var(--background);
        --input-border: var(--border);
    }

    .fleet-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* --- Header --- */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border);
    }

    .page-header h1 { font-size: 2rem; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
    .page-header p { color: var(--text-secondary); margin: 0; }
    
    .fleet-count-badge {
        background: var(--surface);
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid var(--border);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* --- Control Bar (Filters) --- */
    .control-bar {
        background: var(--surface);
        padding: 20px;
        border-radius: var(--card-radius);
        border: 1px solid var(--border);
        margin-bottom: 32px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: var(--shadow-sm);
        align-items: center;
        justify-content: space-between;
    }

    .filter-group { display: flex; gap: 16px; flex-wrap: wrap; flex: 1; }
    .sort-group { min-width: 200px; }

    .input-wrapper {
        position: relative;
        min-width: 180px;
        flex: 1;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        pointer-events: none;
        z-index: 1;
    }

    .custom-select {
        width: 100%;
        padding: 10px 16px 10px 40px; /* Space for icon */
        border-radius: 8px;
        border: 1px solid var(--input-border);
        background: var(--input-bg);
        color: var(--text-primary);
        font-size: 0.95rem;
        cursor: pointer;
        outline: none;
        appearance: none; /* Hides default arrow */
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        transition: all 0.2s;
    }

    .custom-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* --- Grid & Cards --- */
    .grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .car-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--card-radius);
        overflow: hidden;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .car-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .card-image {
        position: relative;
        height: 200px;
        background: var(--background);
        overflow: hidden;
    }

    .card-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .car-card:hover .card-image img { transform: scale(1.05); }

    .status-badge {
        position: absolute; top: 12px; left: 12px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        z-index: 2; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .status-badge.available { background: var(--success); color: white; }

    /* Hover Overlay Effect */
    .card-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .card-overlay span {
        color: white; border: 1px solid white; padding: 8px 16px; border-radius: 20px; font-weight: 500;
        backdrop-filter: blur(4px);
    }
    .car-card:hover .card-overlay { opacity: 1; }

    .card-body { padding: 20px; display: flex; flex-direction: column; flex: 1; }

    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .car-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .car-year { font-size: 0.85rem; color: var(--text-secondary); background: var(--background); padding: 2px 8px; border-radius: 4px; }

    .specs-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 8px; margin-bottom: 16px;
    }
    
    .spec-item {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        text-align: center;
    }
    .spec-item i { color: var(--primary); font-size: 14px; opacity: 0.8; }
    .spec-item span { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; }

    .card-divider { height: 1px; background: var(--border); margin-bottom: 16px; }

    .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    
    .price-info { display: flex; align-items: baseline; }
    .price-info .currency { font-size: 0.9rem; font-weight: 600; color: var(--primary); margin-right: 2px; }
    .price-info .amount { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); }
    .price-info .unit { font-size: 0.8rem; color: var(--text-secondary); margin-left: 4px; }

    .btn-view {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--background); border: none;
        color: var(--primary); display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .car-card:hover .btn-view { background: var(--primary); color: white; }

    /* --- Empty States --- */
    .empty-state {
        text-align: center; padding: 60px 20px;
        background: var(--surface); border-radius: var(--card-radius);
        border: 2px dashed var(--border); width: 100%;
    }
    .empty-icon {
        width: 80px; height: 80px; background: var(--background); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: var(--text-secondary); margin: 0 auto 20px;
    }
    .btn-reset {
        margin-top: 16px; padding: 10px 20px; background: var(--primary); color: white;
        border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none;
    }

    /* --- Responsive --- */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .control-bar { flex-direction: column; align-items: stretch; }
        .filter-group { flex-direction: column; }
        .input-wrapper { width: 100%; }
        .specs-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .spec-item { flex-direction: row; justify-content: center; }
    }
</style>
<?= $this->endSection() ?>