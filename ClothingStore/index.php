<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $adminDestination = "adminDashboard.php";
} else {
    $adminDestination = "adminLogin.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Times | Sustainable Second-hand Clothing</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --forest-green: #1d3c34; --cream: #f9f7f2; --charcoal: #222; }
        body { margin: 0; font-family: 'Montserrat', sans-serif; background-color: var(--cream); color: var(--charcoal); scroll-behavior: smooth; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; background: white; border-top: 10px solid #b2a4d4; position: sticky; top: 0; z-index: 1000; }
        .logo { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: bold; }
        .nav-links { display: flex; gap: 30px; list-style: none; margin: 0; }
        .nav-links a { text-decoration: none; color: var(--forest-green); font-weight: 600; font-size: 15px; }
        .hero { display: flex; height: 550px; background: white; }
        .hero-image { flex: 1.2; background: url('https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=1200') center/cover; }
        .hero-text { flex: 1; padding: 60px; display: flex; flex-direction: column; justify-content: center; background: var(--cream); }
        .hero-text h1 { font-family: 'Playfair Display', serif; font-size: 3.5rem; color: var(--forest-green); margin-bottom: 20px; }
        .btn-green { background: var(--forest-green); color: white; padding: 15px 35px; text-decoration: none; font-weight: bold; width: fit-content; display: inline-block; }
        .mission { display: flex; align-items: center; padding: 100px 50px; background: white; gap: 60px; }
        .mission-content { flex: 1; text-align: center; }
        .mission-content h2 { font-family: 'Playfair Display', serif; font-size: 2.8rem; color: var(--forest-green); }
        .mission-img { flex: 1.2; }
        .mission-img img { width: 100%; border-radius: 2px; box-shadow: 20px 20px 0px var(--forest-green); }
        .testimonials { padding: 100px 50px; background: #1a1a1a; color: white; text-align: center; }
        .testimonial-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; margin-top: 50px; }
        .testimonial-card img { width: 100%; height: 380px; object-fit: cover; margin-bottom: 20px; }
        .staff-area { background: var(--forest-green); padding: 40px; text-align: center; color: white; }
        .staff-area a { color: white; text-decoration: underline; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Past Times</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="<?php echo $adminDestination; ?>">Admin Portal</a></li>
        </ul>
    </nav>

    <section class="hero">
        <div class="hero-image"></div>
        <div class="hero-text">
            <h1>Welcome to Our Clothing Store</h1>
            <p>Explore our curated selection of second-hand clothing that promotes sustainability and helps reduce waste.</p>
            <a href="login.php" class="btn-green">Shop Now</a>
        </div>
    </section>

    <section class="mission">
        <div class="mission-content">
            <h2>Our Sustainable Mission</h2>
            <p>At our store, we believe in fashion that doesn't harm the environment. Our mission is to promote sustainability through second-hand clothing, reducing waste and giving pre-loved garments a second chance.</p>
            <a href="#" class="btn-green">Learn More</a>
        </div>
        <div class="mission-img">
            <img src="https://images.unsplash.com/photo-1532453288672-3a27e9be4efd?auto=format&fit=crop&w=1000" alt="Second hand market">
        </div>
    </section>

    <section class="testimonials">
        <h2>What Our Customers Say</h2>
        <p>Join our community of satisfied shoppers who love our sustainable fashion.</p>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <img src="https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=500" alt="Happy Customer">
                <h3>Amazing Quality</h3>
                <p>"I was pleasantly surprised by the quality! Every piece has been in excellent condition."</p>
            </div>
            <div class="testimonial-card">
                <img src="https://images.unsplash.com/photo-1581067723501-443bc92193e2?auto=format&fit=crop&w=500" alt="Unique Finds">
                <h3>Unique Finds</h3>
                <p>"I've found unique items that I can't find anywhere else. It's like a treasure hunt!"</p>
            </div>
            <div class="testimonial-card">
                <img src="https://images.unsplash.com/photo-1556911220-e15023318f1d?auto=format&fit=crop&w=500" alt="Customer Service">
                <h3>Great Service</h3>
                <p>"The customer service is fantastic! They helped me find exactly what I was looking for."</p>
            </div>
        </div>
    </section>

    <footer class="staff-area">
        <p>© 2026 Past Times Clothing Store | <a href="<?php echo $adminDestination; ?>">Access Admin Dashboard</a></p>
    </footer>
</body>
</html>