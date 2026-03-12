<?php require 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 4rem 1rem;">
    <h1>About Two Wheeler Rental</h1>
    <p>Your trusted partner for two-wheeler rentals</p>
</section>

<!-- Company Description -->
<section class="about-description" style="padding: 3rem 5%; max-width: 800px; margin: 0 auto; text-align: center;">
    <p style="font-size: 1.2rem; color: var(--gray);">
        We provide a wide range of two-wheelers for rent at affordable prices. 
        Whether you need a bike for a day trip or a scooter for daily commute, we've got you covered.
    </p>
</section>

<!-- How It Works Section -->
<section class="how-it-works" style="padding: 3rem 5%; background: #f8fafc;">
    <h2 style="text-align: center; margin-bottom: 2rem;">How It Works</h2>
    <div class="grid-container" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem;">
        <div class="card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
            <h3>Browse</h3>
            <p>Explore our collection of vehicles and find the perfect ride.</p>
        </div>
        <div class="card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
            <h3>Select Dates</h3>
            <p>Choose your rental start and end dates.</p>
        </div>
        <div class="card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📝</div>
            <h3>Submit Request</h3>
            <p>Send a rental request – it's free and easy.</p>
        </div>
        <div class="card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
            <h3>Get Approved</h3>
            <p>Admin approves your request; you'll receive a notification.</p>
        </div>
        <div class="card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🏍️</div>
            <h3>Enjoy Your Ride</h3>
            <p>Pick up the vehicle and hit the road!</p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="contact" style="padding: 3rem 5%;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Contact Us</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;">
        <div class="contact-card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); flex: 1 1 300px;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <span style="font-size: 2rem;">📞</span>
                <div>
                    <h3>Phone</h3>
                    <p>+91 9876543210</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <span style="font-size: 2rem;">✉️</span>
                <div>
                    <h3>Email</h3>
                    <p>support@twowheeler.com</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 2rem;">📍</span>
                <div>
                    <h3>Address</h3>
                    <p>Kathmandu </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>