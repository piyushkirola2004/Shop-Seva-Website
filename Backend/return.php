<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { echo "Not logged in"; exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $reason = $conn->real_escape_string($_POST['reason']);
    $conn->query("INSERT INTO returns (order_id, user_id, reason) VALUES ('$order_id', '$user_id', '$reason')");
    echo "Return request submitted";
}
?>