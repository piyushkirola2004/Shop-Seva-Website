<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Not logged in";
    exit;
}

// Handle POST request to add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($product_id > 0 && $quantity > 0) {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) 
                VALUES ('$user_id', '$product_id', '$quantity') 
                ON DUPLICATE KEY UPDATE quantity = quantity + $quantity";
        $conn->query($sql);

        // Redirect to cart page to show updated cart
        header("Location: cart.php");
        exit;
    }
}

// Fetch cart items
$sql = "SELECT cart.*, products.name, products.price, products.image
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id='$user_id'";
$result = $conn->query($sql);
$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .cart-container { width: 500px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .cart-item { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .cart-item:last-child { border-bottom: none; }
        .cart-total { font-weight: bold; text-align: right; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="cart-container">
    <h2>Your Cart</h2>
    <?php if (empty($cart_items)): ?>
        <p class="empty">Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item" style="display:flex; align-items:center; margin-bottom:15px; border-bottom:1px solid #ddd; padding-bottom:10px;">
                <!-- Product Image -->
                <div class="cart-img" style="flex:0 0 80px; margin-right:15px;">
                    <img src="<?php echo $item['image'] ?? 'assets/A1.jpg'; ?>" alt="<?php echo $item['name']; ?>" style="width:80px; height:80px; object-fit:cover; border-radius:5px;">
                </div>
                <!-- Product Info -->
                <div class="cart-info" style="flex:1;">
                    <strong><?php echo $item['name']; ?></strong><br>
                    Quantity: <?php echo $item['quantity']; ?><br>
                    Price: ₹<?php echo $item['price']; ?>
                </div>
                <!-- Item Total -->
                <div class="cart-price" style="font-weight:bold;">
                    ₹<?php echo $item['price'] * $item['quantity']; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Total Section -->
        <div class="cart-total" style="text-align:right; margin-top:20px; font-size:18px;">
            Subtotal: ₹<?php echo $total_price; ?><br>
            GST (18%): ₹<?php echo round($total_price*0.18,2); ?><br>
            <strong>Total: ₹<?php echo round($total_price*1.18,2); ?></strong>
        </div>
        <a href="order.php" class="checkout-btn" style="
    display:block;
    margin-top:20px;
    width:100%;
    padding:12px;
    background:#ff9900;
    color:#fff;
    text-align:center;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
">Proceed to Checkout</a>
    <?php endif; ?>
</div>
</body>
</html>
