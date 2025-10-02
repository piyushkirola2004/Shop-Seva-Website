<?php
session_start();
include 'config.php';

$order_id = (int)($_GET['order_id'] ?? 0);
if ($order_id <= 0) {
    die("Invalid order ID.");
}

// Fetch order items
$sql = "SELECT oi.*, p.name, p.image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = '$order_id'";
$result = $conn->query($sql);

$order_items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $order_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

// Fetch order info (optional: shipping details)
$order_info = $conn->query("SELECT * FROM orders WHERE id='$order_id'")->fetch_assoc();
