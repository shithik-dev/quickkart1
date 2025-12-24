<?php
require_once __DIR__ . '/../controllers/OrderController.php';
$orderController = new OrderController();
$orders = $orderController->myOrders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="stylesheet" href="../assets/css/product.css">
    <title>My Orders - QUICKKART</title>
    <style>
        :root{--primary:#005eff;--primary-dark:#094acc;--bg:#f7f9fc;--card:#fff;--muted:#64748b;--border:#e2e8f0;--radius:12px;--shadow:0 10px 30px rgba(15,23,42,0.08)}
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
        .muted{color:var(--muted)}

        .card{background:var(--card);border-radius:12px;padding:18px;border:1px solid #eef2f6;box-shadow:var(--shadow)}
        .card .card{padding:14px;margin-bottom:12px;box-shadow:none;border:1px solid var(--border)}

        .space-between{display:flex;align-items:center;justify-content:space-between}

        .pill{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;font-weight:700;font-size:13px}
        .status-pending{background:#fff7ed;color:#c05621;border:1px solid #fcd5a3}
        .status-packed{background:#fef3c7;color:#92400e;border:1px solid #fbe5a0}
        .status-out{background:#e0f2fe;color:#0369a1;border:1px solid #bfe8ff}
        .status-delivered{background:#dcfce7;color:#166534;border:1px solid #bff0c2}

        ul{list-style:none;padding:0;margin:0}
        li.space-between{display:flex;justify-content:space-between;padding:6px 0}

        @media(max-width:800px){.page{padding:0 12px}.card{padding:12px}.section-title{font-size:20px}}
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
                <input type="text" placeholder="Search orders">
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
        <h2 class="section-title">My Orders</h2>
        <?php if (empty($orders)): ?>
            <p>No orders yet.</p>
        <?php else: ?>
        <div class="card" style="padding:18px;">
            <?php foreach ($orders as $order): ?>
            <div class="card" style="padding:14px; margin-bottom:12px; box-shadow: none; border:1px solid var(--border);">
                <div class="space-between" style="margin-bottom:8px;">
                    <div>
                        <div class="muted">Order #<?php echo $order['id']; ?></div>
                        <strong>Placed on: <?php echo $order['created_at']; ?></strong>
                    </div>
                    <?php
                        $statusClass = 'pending';
                        if ($order['status'] === 'Packed') $statusClass = 'packed';
                        elseif ($order['status'] === 'Out for delivery') $statusClass = 'out';
                        elseif ($order['status'] === 'Delivered') $statusClass = 'delivered';
                    ?>
                    <span class="pill status-<?php echo $statusClass; ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </span>
                </div>
                <div class="muted" style="margin-bottom:8px;">Items</div>
                <ul style="list-style:none; padding-left:0; margin:0 0 10px; display:flex; flex-direction:column; gap:6px;">
                    <?php foreach ($order['items'] as $item): ?>
                    <li class="space-between">
                        <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                        <span>₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="space-between" style="border-top:1px solid var(--border); padding-top:8px;">
                    <span>Total</span>
                    <strong>₹<?php echo number_format($order['total_amount'],2); ?></strong>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>

