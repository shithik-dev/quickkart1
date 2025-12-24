<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$debug = '';
$orderController = new OrderController();
$cartController = new CartController();
$cartItems = $cartController->view();
$total = 0;
foreach ($cartItems as $item) { $total += $item['price'] * $item['quantity']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($address) || empty($phone)) {
        $error = 'Please fill in all fields.';
    } else if (empty($cartItems)) {
        $error = 'Your cart is empty.';
    } else {
        $debug = "Cart Items: " . json_encode($cartItems) . "\n";
        $debug .= "User ID: " . $_SESSION['user_id'] . "\n";
        $debug .= "Total: $total\n";
        
        // Process checkout
        $orderId = $orderController->checkout();
        $debug .= "Order ID Result: " . ($orderId ? $orderId : "FALSE/NULL") . "\n";
        
        // Read error log for debugging
        $logFile = ini_get('error_log');
        if ($logFile && file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -10);
            $debug .= "\n\nRecent error log:\n" . implode('', $recentLines);
        }
        
        if ($orderId) {
            // Success - redirect to orders page
            header("Location: orders.php?success=1");
            exit;
        } else {
            $error = 'Failed to place order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="stylesheet" href="../assets/css/product.css">
    <title>Checkout - QUICKKART</title>
    <style>
        :root{--primary:#005eff;--primary-dark:#094acc;--bg:#f7f9fc;--card:#fff;--muted:#64748b;--radius:12px;--shadow:0 10px 30px rgba(15,23,42,0.08)}
        *{box-sizing:border-box}
        body{font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;background:var(--bg);color:#0f172a;margin:0}

        /* Navbar */
        .navbar{background:linear-gradient(90deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 0;position:sticky;top:0;z-index:40}
        .navbar-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;align-items:center;gap:12px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:18px}
        .brand svg{width:28px;height:28px}
        .search{display:flex;gap:8px;align-items:center;flex:1;max-width:520px;margin:0 16px}
        .search input{flex:1;padding:8px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);color:#fff}
        .search button{background:rgba(255,255,255,0.12);color:#fff;border:1px solid transparent;padding:8px 12px;border-radius:8px;cursor:pointer}
        .nav-actions{display:flex;align-items:center;gap:12px}
        .nav-menu a{color:rgba(255,255,255,0.95);margin-right:12px;font-weight:600}
        .icon-btn{background:transparent;border:1px solid rgba(255,255,255,0.08);padding:8px;border-radius:10px;color:#fff;cursor:pointer}

        .page{max-width:1200px;margin:28px auto;padding:0 16px}
        .section-title{font-size:24px;margin-bottom:18px}

        /* Checkout layout */
        .cart-layout{display:grid;grid-template-columns:1fr 320px;gap:20px}
        .address-card{background:var(--card);border-radius:12px;padding:14px;border:1px solid #eef2f6;box-shadow:var(--shadow);margin-bottom:16px}
        .input{width:100%;padding:10px 12px;border:1px solid #eef2f6;border-radius:8px}

        .table-card{background:var(--card);border-radius:12px;padding:14px;border:1px solid #eef2f6;box-shadow:var(--shadow)}
        .cart-table{width:100%;border-collapse:collapse}
        .cart-table thead th{padding:12px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f6}
        .cart-table td{padding:12px;vertical-align:middle;border-bottom:1px solid #f3f6f9}

        .summary-card{background:var(--card);border-radius:12px;padding:18px;border:1px solid #eef2f6;box-shadow:var(--shadow);height:fit-content}
        .summary-row{display:flex;justify-content:space-between;padding:8px 0;color:var(--muted)}
        .summary-total{display:flex;justify-content:space-between;padding-top:12px;border-top:1px dashed #eef2f6;font-weight:800;font-size:18px}

        .btn{display:inline-flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;border:1px solid transparent;cursor:pointer;font-weight:700}
        .btn-primary{background:var(--primary);color:#fff;width:100%;text-align:center}
        .btn-outline{background:#fff;border:1px solid #e6eef8;color:var(--text);display:inline-block;padding:10px 14px;border-radius:10px}

        .alert{padding:12px;border-left:4px solid; border-radius:8px}
        .alert-error{background:#fee2e2;color:#991b1b;border-left-color:var(--danger)}

        @media(max-width:900px){.cart-layout{grid-template-columns:1fr}.search{display:none}}
    </style>
    <script defer src="../assets/js/script.js"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                <svg viewBox="0 0 24 24" fill="none"><path fill="#fff" d="M6 6h15l-1.5 9h-11z"/><circle cx="9" cy="19" r="1.5" fill="#fff"/><circle cx="17" cy="19" r="1.5" fill="#fff"/><path stroke="#fff" stroke-width="1.6" stroke-linecap="round" d="M4 4h2l1 10h11.5"/></svg>
                QUICKKART
            </div>
            <div class="search">
                <input type="text" placeholder="Search products">
                <button>Search</button>
            </div>
            <div class="nav-actions">
                <div class="nav-menu">
                    <a href="index.php">Home</a>
                    <a href="cart.php">Cart</a>
                </div>
                <button class="icon-btn" aria-label="Login">
                    <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-6 1.67-6 3.75V20h12v-2.25C18 15.67 15.33 14 12 14z" fill="white"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="page">
        <h2 class="section-title">Checkout</h2>
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
        <form method="post">
            <div class="cart-layout">
                <div>
                    <div class="address-card">
                        <div class="space-between">
                            <h3>Delivery Address</h3>
                            <a class="muted" href="#">Change</a>
                        </div>
                        <textarea class="input" name="address" rows="3" placeholder="Enter delivery address" required></textarea>
                        <input class="input" type="text" name="phone" placeholder="Phone number" required>
                    </div>
                    <div class="card table-card">
                        <table class="cart-table">
                            <thead>
                                <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="summary-card">
                    <h3 style="margin-bottom:10px;">Order Summary</h3>
                    <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($total,2); ?></span></div>
                    <div class="summary-row"><span>Delivery</span><span>₹0</span></div>
                    <div class="summary-row"><span>COD</span><span>Available</span></div>
                    <div class="summary-total"><span>Total</span><span>₹<?php echo number_format($total,2); ?></span></div>
                    <button class="btn btn-primary" style="width:100%; margin-top:14px;" type="submit">Place Order (COD)</button>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error" style="margin-top:10px;"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($debug)): ?>
                        <div style="margin-top:10px; padding:10px; background:#f0f0f0; border:1px solid #ccc; border-radius:8px; font-size:12px; white-space:pre-wrap; word-break:break-word; font-family:monospace;">
                            DEBUG:<br><?php echo htmlspecialchars($debug); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>

