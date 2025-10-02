<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("You must be logged in to make a payment.");
}

// Fetch cart items for this user
$result = $conn->query("
    SELECT cart.*, products.name, products.price, products.image
    FROM cart 
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id='$user_id'
");

$cart_items = [];
$total_price = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';

    // Simple validation
    if (empty($payment_method)) {
        $error = "Please select a payment method.";
    } else {
        // Optional: Capture payment details
        $payment_details = [];
        if ($payment_method === 'card') {
            $payment_details['card_number'] = $_POST['card_number'] ?? '';
            $payment_details['card_name'] = $_POST['card_name'] ?? '';
            $payment_details['expiry'] = $_POST['expiry'] ?? '';
            $payment_details['cvv'] = $_POST['cvv'] ?? '';
        } elseif ($payment_method === 'netbank') {
            $payment_details['bank_name'] = $_POST['bank_name'] ?? '';
            $payment_details['account_number'] = $_POST['account_number'] ?? '';
        } elseif ($payment_method === 'upi') {
            $payment_details['upi_id'] = $_POST['upi_id'] ?? '';
        }

        // Insert order
        $conn->query("INSERT INTO orders (user_id, total, payment_method, status) 
                     VALUES ('$user_id', '$total_price', '$payment_method', 'Paid')");
        $order_id = $conn->insert_id;

        // Insert order items
        foreach ($cart_items as $item) {
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                         VALUES ('{$order_id}', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')");
        }

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id='$user_id'");

        // Redirect to order success page
        header("Location: order_success.php?order_id={$order_id}");
        exit;
    }
}
