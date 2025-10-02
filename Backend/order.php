<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("You must be logged in to place an order.");
}

// Fetch cart items for this user
$sql = "SELECT cart.*, products.name, products.price, products.image
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id='$user_id'";
$result = $conn->query($sql);
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

if (empty($cart_items)) {
    die("Your cart is empty.");
}

// ---- START HTML ----
?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .order-container {
            width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .order-item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            display: flex;
            align-items: center;
        }

        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .order-total {
            font-weight: bold;
            text-align: right;
            margin-top: 15px;
        }

        .checkout-btn {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #ff9900;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }
        .checkout-btn {
            background-color: #f0c14b; 
            border: 1px solid #a88734; 
            padding: 10px 20px;
            font-size: 16px; 
            border-radius: 3px; 
            cursor: pointer; 
            transition: background 0.3s; 
        }

        .checkout-btn:hover {
            background-color: #e2b33c;
        }

        </style>
</head>
<body>
    <div class="order-container">
        <h2>Your Order</h2>
        <?php foreach ($cart_items as $item): ?>
            <div class="order-item">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                <div>
                    <div><?php echo $item['name']; ?></div>
                    <div>Qty: <?php echo $item['quantity']; ?></div>
                    <div>₹<?php echo $item['price'] * $item['quantity']; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php
        $total_price = 0;
        foreach ($cart_items as $i) {
            $total_price += $i['price'] * $i['quantity'];
        }
        ?>
        <div class="order-total">Total: ₹<?php echo $total_price; ?></div>
        <form action="place_order.php" method="POST">
            <button type="submit" class="checkout-btn">Place Order</button>
        </form>
    </div>
</body>

</html>