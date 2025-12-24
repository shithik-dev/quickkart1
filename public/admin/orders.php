<?php
require_once __DIR__ . '/../../controllers/AdminController.php';
$admin = new AdminController();
$orders = $admin->orders();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'clear_all') {
        $admin->clearAllOrders();
    } else {
        $admin->updateOrderStatus();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - QUICKKART</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #005eff;
            --primary-dark: #094acc;
            --primary-light: #e8f2ff;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #0f172a;
            --text-muted: #64748b;
            --bg: #f7f9fc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --shadow-sm: 0 4px 12px rgba(15, 23, 42, 0.05);
            --radius: 14px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: var(--transition);
        }

        /* Header Styles */
        header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header > div {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-links a {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-muted);
            position: relative;
            padding: 8px 0;
        }

        .nav-links a:not(.btn):hover {
            color: var(--primary);
        }

        .nav-links a:not(.btn)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-links a:not(.btn):hover::after {
            width: 100%;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 8px 16px rgba(0, 94, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: var(--card-bg);
            border-color: var(--border);
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--bg);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 16px;
        }

        h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 32px;
        }

        /* Order Card */
        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .order-card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        /* Order Header */
        .order-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .order-header-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-header-item label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .order-header-item .value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .order-header-item .order-id {
            font-size: 18px;
            color: var(--primary);
        }

        .order-header-item .total {
            font-size: 20px;
            color: var(--success);
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff7ed;
            color: #c05621;
            border: 1px solid #fecaca;
        }

        .status-packed {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-out {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* Order Items */
        .order-items {
            margin-bottom: 20px;
        }

        .order-items h4 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .items-list {
            list-style: none;
            display: grid;
            gap: 8px;
        }

        .items-list li {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 12px;
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
            font-size: 14px;
            align-items: center;
        }

        .items-list .product-name {
            font-weight: 600;
            color: var(--text);
        }

        .items-list .quantity {
            color: var(--text-muted);
            font-weight: 600;
        }

        .items-list .price {
            color: var(--primary);
            font-weight: 700;
        }

        /* Order Footer */
        .order-footer {
            display: flex;
            gap: 12px;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .status-select {
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            background: var(--bg);
            color: var(--text);
            cursor: pointer;
            transition: var(--transition);
        }

        .status-select:hover,
        .status-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .order-header {
                grid-template-columns: 1fr 1fr;
            }

            .order-footer {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            header > div {
                padding: 0 12px;
                height: 60px;
            }

            .logo {
                font-size: 18px;
            }

            .nav-links {
                gap: 16px;
            }

            .nav-links a {
                font-size: 13px;
            }

            h2 {
                font-size: 24px;
            }

            .order-card {
                padding: 16px;
            }

            .order-header {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 16px;
                padding-bottom: 16px;
            }

            .items-list li {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .order-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .status-select,
            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 12px;
            }

            h2 {
                font-size: 20px;
            }

            .order-card {
                padding: 14px;
            }

            .order-header {
                gap: 10px;
                margin-bottom: 12px;
                padding-bottom: 12px;
            }

            .order-header-item .value {
                font-size: 14px;
            }

            .order-header-item .order-id {
                font-size: 16px;
            }

            .items-list li {
                padding: 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
<header>
    <div>
        <div class="logo">Admin - QUICKKART</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="categories.php">Categories</a>
            <a href="../logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
</header>
<div class="container">
    <h2>Orders</h2>
    <?php if (!empty($orders)): ?>
        <form method="post" style="margin-bottom: 20px;" onsubmit="return confirm('Are you sure you want to delete all orders? This cannot be undone.');">
            <input type="hidden" name="action" value="clear_all">
            <button class="btn" style="background:#ef4444;color:#fff;padding:10px 18px;border-radius:10px;border:none;cursor:pointer;font-weight:700;" type="submit">🗑️ Clear All Orders</button>
        </form>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>No orders found. Orders will appear here once customers place them.</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-header-item">
                        <label>Order ID</label>
                        <div class="order-id">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                    </div>
                    <div class="order-header-item">
                        <label>Customer Name</label>
                        <div class="value"><?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="order-header-item">
                        <label>Total Amount</label>
                        <div class="total">₹<?php echo number_format($order['total_amount'], 2); ?></div>
                    </div>
                </div>

                <div class="order-items">
                    <h4>Order Items</h4>
                    <ul class="items-list">
                        <?php foreach ($order['items'] as $item): ?>
                            <li>
                                <span class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                <span class="quantity">Qty: <?php echo $item['quantity']; ?></span>
                                <span class="price">₹<?php echo number_format($item['price'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="order-footer">
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </span>
                    <form method="post" style="display: flex; gap: 12px; flex: 1;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" class="status-select">
                            <?php foreach (['Pending', 'Packed', 'Out for delivery', 'Delivered'] as $status): ?>
                                <option value="<?php echo $status; ?>" <?php if ($order['status'] === $status) echo 'selected'; ?>>
                                    <?php echo $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary" type="submit">Update Status</button>
                    </form>
                    <?php if ($order['id'] == 2): ?>
                        <form method="post" style="display: flex;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="status" value="Pending">
                            <button class="btn btn-outline" type="submit" title="Reset order to Pending status">🔄 Reset</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>

