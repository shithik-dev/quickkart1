<?php
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
$cartController = new CartController();
$productController = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') $cartController->add();
    if ($action === 'update') $cartController->update();
}
if (($_GET['action'] ?? '') === 'remove') { $cartController->remove(); }

$items = $cartController->view();
$total = 0;
foreach ($items as $item) { $total += $item['price'] * $item['quantity']; }
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
    <title>Cart - QUICKKART</title>
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

        /* Page */
        .page{max-width:1200px;margin:28px auto;padding:0 16px}
        .section-title{font-size:24px;margin-bottom:18px}

        /* Cart layout */
        .cart-layout{display:grid;grid-template-columns:1fr 320px;gap:20px}
        .table-card{background:var(--card);border-radius:12px;padding:14px;border:1px solid #eef2f6;box-shadow:var(--shadow)}
        .cart-table{width:100%;border-collapse:collapse}
        .cart-table thead th{padding:12px;text-align:left;font-weight:700;border-bottom:1px solid #eef2f6}
        .cart-table td{padding:12px;vertical-align:middle;border-bottom:1px solid #f3f6f9}
        .cart-item-img{width:72px;height:72px;object-fit:cover;border-radius:8px}

        .qty{display:inline-flex;align-items:center;border:1px solid #e6eef8;border-radius:8px;overflow:hidden}
        .qty button{background:transparent;border:0;padding:8px 12px;cursor:pointer;font-weight:700}
        .qty input{width:56px;padding:8px;border:0;text-align:center}

        /* Summary card */
        .summary-card{background:var(--card);border-radius:12px;padding:18px;border:1px solid #eef2f6;box-shadow:var(--shadow);height:fit-content}
        .summary-row{display:flex;justify-content:space-between;padding:8px 0;color:var(--muted)}
        .summary-total{display:flex;justify-content:space-between;padding-top:12px;border-top:1px dashed #eef2f6;font-weight:800;font-size:18px}

        .btn{display:inline-flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;border:1px solid transparent;cursor:pointer;font-weight:700}
        .btn-primary{background:var(--primary);color:#fff;width:100%;text-align:center}
        .btn-outline{background:#fff;border:1px solid #e6eef8;color:var(--text);display:inline-block;padding:10px 14px;border-radius:10px}

        @media(max-width:900px){
            .cart-layout{grid-template-columns:1fr}
            .search{display:none}
        }
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
                    <a href="category.php">Shop</a>
                </div>
                <button class="icon-btn" aria-label="Login">
                    <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-6 1.67-6 3.75V20h12v-2.25C18 15.67 15.33 14 12 14z" fill="white"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="page">
        <h2 class="section-title">Your Cart</h2>
        <?php if (empty($items)): ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
        <?php else: ?>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <div class="cart-layout">
                <div class="card table-card">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="flex" style="gap:12px;">
                                    <img class="cart-item-img" src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <div class="muted">₹<?php echo number_format($item['price'],2); ?></div>
                                    </div>
                                </td>
                                <td>₹<?php echo number_format($item['price'],2); ?></td>
                                <td>
                                    <div class="qty">
                                        <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                        <input type="number" name="items[<?php echo $item['cart_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                                        <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                                    </div>
                                </td>
                                <td><strong>₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></strong></td>
                                <td><a class="btn btn-outline" href="cart.php?action=remove&id=<?php echo $item['cart_id']; ?>">Remove</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="summary-card">
                    <h3 style="margin-bottom:10px;">Order Summary</h3>
                    <div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($total,2); ?></span></div>
                    <div class="summary-row"><span>Delivery</span><span>₹0</span></div>
                    <div class="summary-row"><span>COD</span><span>Available</span></div>
                    <div class="summary-total"><span>Total</span><span>₹<?php echo number_format($total,2); ?></span></div>
                    <button class="btn btn-primary" style="width:100%; margin-top:14px;" type="submit">Update Cart</button>
                    <a class="btn btn-outline" style="width:100%; margin-top:10px; text-align:center;" href="checkout.php">Proceed to Checkout</a>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>

