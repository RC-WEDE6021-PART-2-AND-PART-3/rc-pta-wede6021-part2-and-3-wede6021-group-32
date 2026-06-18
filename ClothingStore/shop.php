<?php
session_start();
include 'DBConn.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart from POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($item_id > 0) {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] += $quantity;
        } else {
            $_SESSION['cart'][$item_id] = $quantity;
        }
    }
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop | Past Times - Sustainable Second-hand Clothing</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --forest: #1d3c34; 
            --cream: #f9f7f2; 
            --gold: #a68a64; 
            --light-gray: #f5f5f5;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Montserrat', sans-serif; 
            background: var(--cream); 
        }
        
        /* Navigation */
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 20px 50px; 
            background: white; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .logo { 
            font-family: 'Playfair Display', serif; 
            font-size: 28px; 
            font-weight: bold; 
            color: var(--forest); 
        }
        
        .nav-links { 
            display: flex; 
            gap: 30px; 
            list-style: none; 
            align-items: center;
            flex-wrap: wrap;
        }
        
        .nav-links a { 
            text-decoration: none; 
            color: var(--forest); 
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .nav-links a:hover { color: var(--gold); }
        
        .user-info { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            flex-wrap: wrap;
        }
        
        .user-info span { 
            color: var(--forest); 
            font-weight: 600; 
        }
        
        .btn { 
            background: var(--forest); 
            color: white; 
            padding: 8px 20px; 
            text-decoration: none; 
            display: inline-block; 
            border-radius: 4px; 
            border: none; 
            cursor: pointer; 
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover { 
            background: var(--gold); 
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--forest);
            color: var(--forest);
        }
        
        .btn-outline:hover {
            background: var(--forest);
            color: white;
        }

        /* Nav Cart Badge */
        .nav-cart {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cart-count-badge {
            background: var(--gold);
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
            position: absolute;
            top: -10px;
            right: -15px;
            transition: transform 0.3s ease;
        }

        /* Floating Cart Button */
        .floating-cart-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--forest);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 99;
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .floating-cart-btn:hover {
            background: var(--gold);
            transform: scale(1.1);
        }

        .floating-cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(rgba(29,60,52,0.85), rgba(29,60,52,0.85)), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=2000');
            background-size: cover;
            background-position: center;
            padding: 80px 50px;
            text-align: center;
            color: white;
        }
        
        .hero-banner h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .hero-banner p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        /* Categories */
        .categories {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 30px 20px;
            background: white;
            flex-wrap: wrap;
            border-bottom: 1px solid #eee;
        }
        
        .category-btn {
            padding: 10px 25px;
            background: var(--light-gray);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            color: #555;
            transition: all 0.3s;
        }
        
        .category-btn:hover, .category-btn.active {
            background: var(--forest);
            color: white;
        }
        
        /* Section Titles */
        .section-title {
            text-align: center;
            padding: 40px 20px 20px;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--forest);
        }
        
        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
        }
        
        /* Product Grid */
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 30px; 
            padding: 20px 50px 50px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .product-card { 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--gold);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .product-card img { 
            width: 100%; 
            height: 280px; 
            object-fit: cover; 
            transition: transform 0.5s;
        }
        
        .product-card:hover img {
            transform: scale(1.05);
        }
        
        .product-card [style*="position: relative"] {
            overflow: hidden;
        }
        
        .product-info { 
            padding: 20px; 
            text-align: center;
            position: relative;
        }
        
        .product-category {
            font-size: 0.7rem;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .product-info h3 { 
            margin: 0 0 10px; 
            color: var(--forest);
            font-size: 1.2rem;
        }
        
        .product-description {
            font-size: 0.85rem;
            color: #666;
            margin: 10px 0;
            line-height: 1.4;
        }
        
        .price { 
            font-size: 1.4rem; 
            font-weight: bold; 
            color: var(--gold); 
            margin: 10px 0;
        }
        
        .old-price {
            font-size: 0.9rem;
            color: #999;
            text-decoration: line-through;
            margin-right: 8px;
        }
        
        .rating {
            margin: 10px 0;
            color: #f39c12;
            font-size: 0.9rem;
        }
        
        .add-to-cart-form {
            width: 100%;
            margin-top: 10px;
        }
        
        .add-to-cart-btn {
            width: 100%;
            padding: 12px;
            background: var(--forest);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .add-to-cart-btn:hover {
            background: var(--gold);
        }
        
        /* Footer */
        footer { 
            background: var(--forest); 
            padding: 40px 50px 20px; 
            color: white; 
            margin-top: 40px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-section h4 {
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        
        .footer-section p, .footer-section a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            line-height: 1.8;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        @media (max-width: 768px) {
            nav { flex-direction: column; gap: 15px; padding: 20px; }
            .product-grid { padding: 20px; gap: 20px; }
            .hero-banner { padding: 50px 20px; }
            .hero-banner h1 { font-size: 2rem; }
            .categories { gap: 10px; }
            .category-btn { padding: 8px 16px; font-size: 0.8rem; }
            .floating-cart-btn { bottom: 20px; right: 20px; width: 50px; height: 50px; font-size: 20px; }
        }
    </style>
</head>
<body>
    <!-- Floating Cart Button -->
    <a href="cart.php" class="floating-cart-btn" id="floatingCartBtn" title="View Cart">
        🛒<span class="floating-cart-badge" id="floatingCartCount"><?php echo array_sum($_SESSION['cart'] ?? []); ?></span>
    </a>

    <nav>
        <div class="logo">Past Times</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php" style="color: var(--gold);">Shop</a></li>
            <li>
                <a href="cart.php" class="nav-cart">
                    Cart 🛒<span class="cart-count-badge" id="navCartCount"><?php echo array_sum($_SESSION['cart'] ?? []); ?></span>
                </a>
            </li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
        <div class="user-info">
            <?php if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn" style="background: #c0392b;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Sign In</a>
                <a href="register.php" class="btn btn-outline">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero-banner">
        <h1>✨ Second-Hand, First Love ✨</h1>
        <p>Discover unique pre-loved treasures that tell a story. Sustainable fashion at its finest.</p>
        <a href="#products" class="btn" style="background: var(--gold);">Shop Now →</a>
    </div>

    <div class="categories" id="categories">
        <button class="category-btn active" data-category="all">All Items</button>
        <button class="category-btn" data-category="women">👗 Women's Fashion</button>
        <button class="category-btn" data-category="men">👔 Men's Fashion</button>
        <button class="category-btn" data-category="accessories">👜 Accessories</button>
        <button class="category-btn" data-category="sale">🏷️ On Sale</button>
    </div>

    <h2 class="section-title" id="products">Our Curated Collection</h2>
    <p class="section-subtitle">Each piece is unique - once it's gone, it's gone forever</p>

    <div class="product-grid" id="productGrid">
        <!-- WOMEN'S FASHION -->
        <div class="product-card" data-category="women">
            <div style="position: relative;">
                <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=500" alt="Vintage Floral Dress">
                <div class="product-badge">Best Seller</div>
            </div>
            <div class="product-info">
                <div class="product-category">👗 Women's Dress</div>
                <h3>Vintage Floral Maxi Dress</h3>
                <div class="product-description">Beautiful 70s-inspired floral print, flowy silhouette, perfect for summer days or garden parties.</div>
                <div class="rating">★★★★★ (47 reviews)</div>
                <div class="price">R42.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="2">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="women">
            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500" alt="Cashmere Sweater">
            <div class="product-info">
                <div class="product-category">👗 Women's Knitwear</div>
                <h3>Cashmere Blend Sweater</h3>
                <div class="product-description">Soft, warm, and timeless. 70% wool, 30% cashmere blend. Perfect for cozy days.</div>
                <div class="rating">★★¼☆ (32 reviews)</div>
                <div class="price">R52.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="3">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="women">
            <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=500" alt="High Waisted Jeans">
            <div class="product-info">
                <div class="product-category">👗 Women's Denim</div>
                <h3>Vintage High-Waisted Jeans</h3>
                <div class="product-description">90s-inspired fit, authentic vintage wash. Sizes 26-32 available.</div>
                <div class="rating">★★★★☆ (28 reviews)</div>
                <div class="price">R38.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="4">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="women">
            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500" alt="Leather Jacket">
            <div class="product-info">
                <div class="product-category">👗 Women's Outerwear</div>
                <h3>Genuine Leather Moto Jacket</h3>
                <div class="product-description">Classic black leather jacket, broken-in feel, genuine leather. Size M.</div>
                <div class="rating">★★★★★ (19 reviews)</div>
                <div class="price">R89.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="5">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <!-- MEN'S FASHION -->
        <div class="product-card" data-category="men">
            <img src="https://images.unsplash.com/photo-1532453288672-3a27e9be4efd?w=500" alt="Denim Jacket">
            <div class="product-info">
                <div class="product-category">👔 Men's Jackets</div>
                <h3>Vintage Denim Trucker Jacket</h3>
                <div class="product-description">Classic Levi's style, authentic fading, excellent condition. Size L.</div>
                <div class="rating">★★★★★ (56 reviews)</div>
                <div class="price">R45.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="6">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="men">
            <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=500" alt="Oxford Shirt">
            <div class="product-info">
                <div class="product-category">👔 Men's Shirts</div>
                <h3>Classic Oxford Button-Down</h3>
                <div class="product-description">Crisp white Oxford shirt, 100% cotton, perfect for work or weekend.</div>
                <div class="rating">★★★★☆ (34 reviews)</div>
                <div class="price">R28.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="7">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="men">
            <img src="https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500" alt="Chino Pants">
            <div class="product-info">
                <div class="product-category">👔 Men's Bottoms</div>
                <h3>Slim Fit Chino Pants</h3>
                <div class="product-description">Khaki chinos, slim fit, like-new condition. Waist 32, 34 available.</div>
                <div class="rating">★★★★☆ (22 reviews)</div>
                <div class="price">R32.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="8">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="men">
            <img src="https://images.unsplash.com/photo-1539533113208-f6df8cc8b543?w=500" alt="Wool Coat">
            <div class="product-info">
                <div class="product-category">👔 Men's Outerwear</div>
                <h3>Wool Peacoat</h3>
                <div class="product-description">Navy blue wool peacoat, double-breasted, perfect for winter. Size M.</div>
                <div class="rating">★★★★★ (15 reviews)</div>
                <div class="price">R75.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="9">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <!-- ACCESSORIES -->
        <div class="product-card" data-category="accessories">
            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500" alt="Leather Bag">
            <div class="product-info">
                <div class="product-category">👜 Bags</div>
                <h3>Genuine Leather Shoulder Bag</h3>
                <div class="product-description">Cognac brown leather, spacious interior, vintage brass hardware.</div>
                <div class="rating">★★★★★ (63 reviews)</div>
                <div class="price">R48.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="10">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="accessories sale">
            <div style="position: relative;">
                <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500" alt="Sunglasses">
                <div class="product-badge" style="background: #e74c3c;">SALE!</div>
            </div>
            <div class="product-info">
                <div class="product-category">🕶️ Eyewear</div>
                <h3>Retro Round Sunglasses</h3>
                <div class="product-description">Vintage-inspired round frames, UV400 protection, gold metal frame.</div>
                <div class="rating">★★★★☆ (41 reviews)</div>
                <div class="price">
                    <span class="old-price">R35.00</span>
                    <span style="color: #e74c3c;">R18.00</span>
                </div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="11">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="accessories">
            <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=500" alt="Leather Boots">
            <div class="product-info">
                <div class="product-category">👢 Footwear</div>
                <h3>Leather Chelsea Boots</h3>
                <div class="product-description">Classic Chelsea boots, genuine leather, elastic side panels. Size 8,9.</div>
                <div class="rating">★★★★★ (38 reviews)</div>
                <div class="price">R65.00</div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="12">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>

        <div class="product-card" data-category="accessories sale">
            <div style="position: relative;">
                <img src="https://images.unsplash.com/photo-1520903928024-664f0a2441e4?w=500" alt="Wool Scarf">
                <div class="product-badge" style="background: #e74c3c;">SALE!</div>
            </div>
            <div class="product-info">
                <div class="product-category">🧣 Accessories</div>
                <h3>Cashmere Blend Scarf</h3>
                <div class="product-description">Soft plaid scarf, 70% wool 30% cashmere, burgundy and cream.</div>
                <div class="rating">★★★★☆ (27 reviews)</div>
                <div class="price">
                    <span class="old-price">R30.00</span>
                    <span style="color: #e74c3c;">R15.00</span>
                </div>
                <form method="POST" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="item_id" value="13">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-btn">Add to Cart 🛒</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Past Times</h4>
                <p>Sustainable second-hand clothing store. Giving pre-loved fashion a second chance since 2024.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <p><a href="index.php">Home</a></p>
                <p><a href="shop.php">Shop</a></p>
                <p><a href="about.html">About Us</a></p>
                <p><a href="team.html">Our Team</a></p>
            </div>
            <div class="footer-section">
                <h4>Customer Service</h4>
                <p>📧 hello@pasttimes.com</p>
                <p>📞 (555) 123-4567</p>
                <p>📍 Cape Town, South Africa</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <p>📷 Instagram</p>
                <p>👍 Facebook</p>
                <p>🐦 Twitter</p>
                <p>🎵 TikTok</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Past Times | Sustainable Fashion for a Better Future ♻️</p>
        </div>
    </footer>

    <script>
        // Category filtering functionality
        const categoryBtns = document.querySelectorAll('.category-btn');
        const products = document.querySelectorAll('.product-card');
        
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                categoryBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const category = btn.getAttribute('data-category');
                
                products.forEach(product => {
                    const productCategory = product.getAttribute('data-category');
                    
                    if (category === 'all') {
                        product.style.display = 'block';
                    } else if (productCategory && productCategory.includes(category)) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });

        // Notification helper (for form submissions)
        <?php if (isset($_POST['add_to_cart'])): ?>
            // Show a small notification
            const notif = document.createElement('div');
            notif.style.position = 'fixed';
            notif.style.bottom = '80px';
            notif.style.right = '30px';
            notif.style.background = '#1d3c34';
            notif.style.color = 'white';
            notif.style.padding = '12px 24px';
            notif.style.borderRadius = '8px';
            notif.style.zIndex = '1000';
            notif.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
            notif.style.animation = 'slideIn 0.3s ease';
            notif.innerHTML = '✅ Added item to cart!';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transition = 'opacity 0.3s ease';
                setTimeout(() => notif.remove(), 300);
            }, 2000);
            
            // Add animation style if not already present
            if (!document.getElementById('notif-style')) {
                const style = document.createElement('style');
                style.id = 'notif-style';
                style.textContent = `@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }`;
                document.head.appendChild(style);
            }
        <?php endif; ?>
    </script>
</body>
</html>