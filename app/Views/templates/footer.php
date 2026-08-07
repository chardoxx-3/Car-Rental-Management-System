<footer style="background: var(--surface); border-top: 1px solid var(--border); margin-top: 80px;">
    <div class="container">
        <div style="padding: 60px 0 40px 0;">
            <div class="grid grid-4">
                <!-- Company Info -->
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-car" style="color: white; font-size: 16px;"></i>
                        </div>
                        <span style="font-size: 20px; font-weight: 700; color: var(--text-primary);">DriveRent</span>
                    </div>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 20px;">
                        Your trusted partner for premium car rental services. Experience the journey with comfort and style.
                    </p>
                    <div style="display: flex; gap: 12px;">
                        <a href="#" style="color: var(--text-secondary); transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" style="color: var(--text-secondary); transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" style="color: var(--text-secondary); transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" style="color: var(--text-secondary); transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 style="color: var(--text-primary); margin-bottom: 20px; font-weight: 600;">Quick Links</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="<?= base_url('/customer/dashboard') ?>" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Browse Cars</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">How It Works</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Pricing</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">FAQ</a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 style="color: var(--text-primary); margin-bottom: 20px; font-weight: 600;">Services</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Daily Rental</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Weekly Rental</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Monthly Rental</a>
                        <a href="#" style="color: var(--text-secondary); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Airport Pickup</a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 style="color: var(--text-primary); margin-bottom: 20px; font-weight: 600;">Contact Us</h4>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                            <span style="color: var(--text-secondary);">123 Rental St, City, State 12345</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-phone" style="color: var(--primary);"></i>
                            <span style="color: var(--text-secondary);">+1 (555) 123-4567</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i>
                            <span style="color: var(--text-secondary);">info@driverent.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div style="border-top: 1px solid var(--border); padding: 24px 0; display: flex; justify-content: between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="color: var(--text-secondary); font-size: 14px;">
                &copy; 2024 DriveRent. All rights reserved.
            </div>
            <div style="display: flex; gap: 24px;">
                <a href="#" style="color: var(--text-secondary); text-decoration: none; font-size: 14px; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Privacy Policy</a>
                <a href="#" style="color: var(--text-secondary); text-decoration: none; font-size: 14px; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Terms of Service</a>
                <a href="#" style="color: var(--text-secondary); text-decoration: none; font-size: 14px; transition: color 0.3s ease;" onmouseover="this.style.color='var(--primary)'">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" style="position: fixed; bottom: 24px; right: 24px; width: 48px; height: 48px; background: var(--primary); color: white; border: none; border-radius: 50%; cursor: pointer; display: none; align-items: center; justify-content: center; box-shadow: var(--shadow-lg); transition: all 0.3s ease; z-index: 99;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
    // Back to top button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>