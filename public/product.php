<?php
require_once __DIR__ . '/../controllers/ProductController.php';
$productController = new ProductController();
$id = intval($_GET['id'] ?? 0);
$product = $productController->product($id);
if (!$product) { header("Location: index.php"); exit; }
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
    <title><?php echo htmlspecialchars($product['product_name']); ?> - QUICKKART</title>
    <style>
        :root{
            --primary:#005eff; --primary-dark:#094acc; --bg:#f7f9fc; --card:#ffffff; --muted:#64748b; --radius:12px;
        }
        *{box-sizing:border-box}
        body{font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;background:var(--bg);color:#0f172a;margin:0}

        /* Navbar */
        .navbar{background:linear-gradient(90deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 0;position:sticky;top:0;z-index:40}
        .navbar-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:18px}
        .brand svg{width:28px;height:28px}

        .search{display:flex;gap:8px;align-items:center;flex:1;max-width:520px;margin:0 16px}
        .search input{flex:1;padding:8px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);color:#fff}
        .search button{background:rgba(255,255,255,0.12);color:#fff;border:1px solid transparent;padding:8px 12px;border-radius:8px;cursor:pointer}

        .nav-actions{display:flex;align-items:center;gap:12px}
        .nav-menu a{color:rgba(255,255,255,0.9);margin-right:12px;font-weight:600}
        .icon-btn{background:transparent;border:1px solid rgba(255,255,255,0.08);padding:8px;border-radius:10px;color:#fff;cursor:pointer}
        .icon-btn svg{width:18px;height:18px}

        /* Page layout */
        .page{max-width:1200px;margin:28px auto;padding:0 16px}
        .product-hero{display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start}
        .image-wrap{background:var(--card);border-radius:var(--radius);padding:18px;display:flex;align-items:center;justify-content:center;border:1px solid #eef2f6}
        .image-wrap img{max-width:100%;border-radius:10px;object-fit:cover;max-height:520px}

        .info .tag{display:inline-block;background:var(--primary);color:#fff;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px;margin-bottom:12px}
        .info h1{margin:0 0 8px 0}
        .muted{color:var(--muted);margin:8px 0 16px}

        .price-row{display:flex;align-items:baseline;gap:12px;margin:16px 0}
        .price{font-size:28px;color:var(--primary);font-weight:800}
        .old-price{font-size:14px;color:var(--muted);text-decoration:line-through}

        form.flex{display:flex;flex-wrap:wrap;gap:12px;align-items:center}
        .qty{display:flex;align-items:center;border:1px solid var(--border, #e6eef8);border-radius:8px;overflow:hidden}
        .qty button{background:transparent;border:0;padding:8px 12px;cursor:pointer;font-weight:700}
        .qty input{width:64px;padding:8px;border:0;text-align:center}

        .btn{display:inline-flex;align-items:center;gap:10px;padding:10px 16px;border-radius:10px;border:1px solid transparent;cursor:pointer;font-weight:700}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-outline{background:#fff;border:1px solid #e6eef8;color:var(--text)}

        @media (max-width:900px){
            .product-hero{grid-template-columns:1fr;}
            .search{display:none}
            .image-wrap img{max-height:420px}
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
                <input type="text" placeholder="Search for products">
                <button>Search</button>
            </div>
            <div class="nav-actions">
                <div class="nav-menu">
                    <a href="index.php">Home</a>
                    <a href="category.php">Categories</a>
                </div>
                <button class="icon-btn" aria-label="Cart">
                    <svg viewBox="0 0 24 24"><path d="M7 4h-2l-1 2m0 0-1 2h17l-2 8h-12l-1-2m-1-8h17" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="icon-btn" aria-label="Login">
                    <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-6 1.67-6 3.75V20h12v-2.25C18 15.67 15.33 14 12 14z" fill="white"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="page">
        <section class="product-hero">
            <div class="image-wrap">
                <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="info">
                <div class="tag"><?php echo htmlspecialchars($product['category_name']); ?></div>
                <h1 style="font-size:26px; font-weight:800;"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="muted"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <div class="price-row">
                    <span class="price">₹<?php echo number_format($product['price'],2); ?></span>
                    <span class="old-price">₹<?php echo number_format($product['mrp'],2); ?></span>
                </div>
                <form class="flex" style="gap:12px;" method="post" action="cart.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="qty">
                        <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                    </div>
                    <button class="btn btn-primary" type="submit">Add to Cart</button>
                    <button class="btn btn-outline" type="button">Save</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>

