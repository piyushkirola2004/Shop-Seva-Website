<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>ShopSeva Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .product-card {
            background: #fff;
            padding: 15px;
            width: 220px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .product-card h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .product-card p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        .shop-now-btn {
            background-color: #ff9900;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .shop-now-btn:hover {
            background-color: #e68a00;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center; margin:20px 0;">ShopSeva Products</h1>
    <div class="products">
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <img src="assets/default.png" alt="No Image">
                <?php endif; ?>

                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="shop-now-btn">Shop Now</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
