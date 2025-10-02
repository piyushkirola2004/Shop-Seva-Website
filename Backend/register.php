<!DOCTYPE html>
<html>
<head>
    <title>Register - ShopSeva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 5px;
            width: 350px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #f0c14b;
            border: 1px solid #a88734;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #ddb347;
        }
        .terms {
            font-size: 12px;
            color: #555;
            margin-top: 10px;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: #0073bb;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Sign in or create account</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email or mobile number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Continue</button>
    </form>

    <p class="terms">By continuing, you agree to ShopSeva's <a href="#">Conditions of Use</a> and <a href="#">Privacy Notice</a>.</p>

    <div class="login-link">
        Already a customer? <a href="login.php">Sign in here.</a>
    </div>
</div>

</body>
</html>
