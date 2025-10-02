<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("You must be logged in to place an order.");
}

// Fetch cart items
$result = $conn->query("
    SELECT cart.*, products.price 
    FROM cart 
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id='$user_id'
");

$cart_items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    die("Your cart is empty.");
}

// Insert order
$conn->query("INSERT INTO orders (user_id, total) VALUES ('$user_id', '$total')");
$order_id = $conn->insert_id;

// Insert order items
foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    echo "<pre>";
    print_r($cart_items);
    echo "</pre>";
    $price = $item['price'];
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                  VALUES ('$order_id', '$product_id', '$quantity', '$price')");
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id='$user_id'");

// Redirect or show confirmation
header("Location: order_success.php?order_id=$order_id");
exit;
