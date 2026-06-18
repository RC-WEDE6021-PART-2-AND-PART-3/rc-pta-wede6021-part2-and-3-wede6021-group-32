﻿<?php
session_start();
include 'DBConn.php';

// Handle editing item quantities or removing items
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $item_id = $_POST['item_id'];
    
    if ($_POST['action'] == 'update') {
        $qty = intval($_POST['quantity']);
        if ($qty > 0) {
            $_SESSION['cart'][$item_id] = $qty;
        } else {
            unset($_SESSION['cart'][$item_id]);
        }
    } elseif ($_POST['action'] == 'delete') {
        unset($_SESSION['cart'][$item_id]);
    }
    header("Location: cart.php");
    exit();
}

// If cart is empty, show empty state
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Sample product data for display (in production this would come from database)
$products = [
    1 => ['name' => 'Vintage Tweed Blazer', 'brand' => 'Harris Tweed', 'price' => 850.00],
    2 => ['name' => 'Vintage Floral Maxi Dress', 'brand' => 'Vintage', 'price' => 42.00],
    3 => ['name' => 'Cashmere Blend Sweater', 'brand' => 'Cashmere', 'price' => 52.00],
    4 => ['name' => 'Vintage High-Waisted Jeans', 'brand' => 'Levi\'s', 'price' => 38.00],
    5 => ['name' => 'Genuine Leather Moto Jacket', 'brand' => 'Leather', 'price' => 89.00],
    6 => ['name' => 'Vintage Denim Trucker Jacket', 'brand' => 'Levi\'s', 'price' => 45.00],
    7 => ['name' => 'Classic Oxford Button-Down', 'brand' => 'Oxford', 'price' => 28.00],
    8 => ['name' => 'Slim Fit Chino Pants', 'brand' => 'Chino', 'price' => 32.00],
    9 => ['name' => 'Wool Peacoat', 'brand' => 'Wool', 'price' => 75.00],
    10 => ['name' => 'Genuine Leather Shoulder Bag', 'brand' => 'Leather', 'price' => 48.00],
    11 => ['name' => 'Retro Round Sunglasses', 'brand' => 'Retro', 'price' => 18.00],
    12 => ['name' => 'Leather Chelsea Boots', 'brand' => 'Chelsea', 'price' => 65.00],
    13 => ['name' => 'Cashmere Blend Scarf', 'brand' => 'Cashmere', 'price' => 15.00],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Bag | Past Times</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --forest: #1d3c34; --gold: #a68a64; --cream: #f9f7f2; }
        body { font-family: 'Montserrat', sans-serif; background: var(--cream); margin: 0; padding: 40px; }
        .cart-container { max-width: 900px; margin: 0 auto; background: white; padding: 40px; box-shadow: 15px 15px 0 var(--gold); }
        h1 { font-family: 'Playfair Display', serif; color: var(--forest); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { border-bottom: 2px solid var(--forest); text-align: left; padding: 10px; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
        td { padding: 15px 10px; border-bottom: 1px solid #ddd; }
        .qty-input { width: 50px; padding: 5px; text-align: center; }
        .btn-inline { background: none; border: none; color: var(--forest); cursor: pointer; font-weight: bold; text-decoration: underline; font-size: 0.85rem; }
        .btn-inline.delete { color: #d9534f; }
        .actions-flex { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 10px; }
        .btn-main { background: var(--forest); color: white; padding: 15px 30px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: none; cursor: pointer; display: inline-block; }
        .btn-secondary { color: var(--forest); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
        .empty-cart { text-align: center; padding: 40px 0; }
        .empty-cart h2 { color: var(--forest); font-family: 'Playfair Display', serif; }
        .empty-cart .btn-main { margin-top: 20px; }
        .cart-total { text-align: right; font-size: 1.2rem; font-weight: bold; color: var(--forest); padding-top: 20px; border-top: 2px solid var(--forest); }
        @media (max-width: 768px) {
            body { padding: 20px; }
            .cart-container { padding: 20px; }
            table { font-size: 0.85rem; }
            td, th { padding: 8px 5px; }
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h1>Your Shopping Bag</h1>
    
    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <h2>🛒 Your cart is empty</h2>
            <p>Start adding some amazing sustainable fashion items!</p>
            <a href="shop.php" class="btn-main">Continue Shopping</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Item Details</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($cart_items as $item_id => $qty): 
                    if (isset($products[$item_id])):
                        $product = $products[$item_id];
                        $subtotal = $product['price'] * $qty;
                        $total += $subtotal;
                ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            <br><small>Brand: <?php echo htmlspecialchars($product['brand']); ?></small>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="number" name="quantity" class="qty-input" value="<?php echo $qty; ?>" min="1">
                                <button type="submit" class="btn-inline">Update</button>
                            </form>
                        </td>
                        <td>R <?php echo number_format($product['price'], 2); ?></td>
                        <td>R <?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-inline delete">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php 
                    endif;
                endforeach; 
                ?>
            </tbody>
        </table>

        <div class="cart-total">
            Total: R <?php echo number_format($total, 2); ?>
        </div>

        <div class="actions-flex">
            <!-- Continue Shopping - goes to shop.php -->
            <a href="shop.php" class="btn-secondary">← Continue Shopping</a>
            <!-- Checkout - goes to home page (index.php) -->
            <a href="index.php" class="btn-main">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>