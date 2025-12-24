<?php
require_once __DIR__ . '/../controllers/ProductController.php';
$productController = new ProductController();

$categoryId = intval($_GET['id'] ?? 0);
$categories = $productController->categories();
$products = $productController->products($categoryId);

$currentName = null;
foreach ($categories as $c) { 
    if ($c['id'] == $categoryId) { 
        $currentName = $c['category_name']; 
        break; 
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
    <title>Category - QUICKKART</title>

    <style>
        :root{
            --primary:#005eff;
            --primary-dark:#094acc;
            --bg:#f7f9fc;
            --card:#fff;
            --muted:#64748b;
            --radius:12px;
            --shadow:0 10px 30px rgba(15,23,42,0.08)
        }

        *{box-sizing:border-box}
        body{
            font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;
            background:var(--bg);
            color:#0f172a;
            margin:0;
        }

        /* NAVBAR */
        .navbar{
            background:linear-gradient(90deg,var(--primary),var(--primary-dark));
            color:#fff;padding:12px 0;
            position:sticky;top:0;z-index:40
        }

        .navbar-inner{
            max-width:1200px;margin:0 auto;
            padding:0 16px;
            display:flex;align-items:center;gap:12px
        }

        .brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:18px}
        .brand svg{width:28px;height:28px}

        .search{display:flex;gap:8px;align-items:center;flex:1;max-width:520px;margin:0 16px}
        .search input{
            flex:1;padding:8px 12px;border-radius:8px;
            border:1px solid rgba(255,255,255,0.12);
            background:rgba(255,255,255,0.06);color:#fff
        }

        .search button{
            background:rgba(255,255,255,0.12);
            color:#fff;border:1px solid transparent;
            padding:8px 12px;border-radius:8px;cursor:pointer
        }

        .nav-actions{display:flex;align-items:center;gap:12px}
        .nav-menu a{color:rgba(255,255,255,0.95);margin-right:12px;font-weight:600}

        .icon-btn{
            background:transparent;border:1px solid rgba(255,255,255,0.08);
            padding:8px;border-radius:10px;color:#fff;cursor:pointer
        }

        /* CONTENT */
        .page{max-width:1200px;margin:28px auto;padding:0 16px}
        .tag{display:inline-block;padding:6px 10px;border-radius:999px;background:#eef7ff;color:var(--primary);font-weight:700}
        .section-title{font-size:22px;margin:6px 0}
        .muted{color:var(--muted)}

        .layout{display:grid;grid-template-columns:260px 1fr;gap:24px}

        .sidebar .card{
            background:var(--card);border-radius:12px;
            padding:16px;border:1px solid #edf2f7;box-shadow:var(--shadow)
        }

        .sidebar ul{list-style:none;padding:0;margin:12px 0}
        .sidebar li{margin-bottom:8px}
        .sidebar a{
            display:flex;gap:8px;align-items:center;
            color:#0f172a;text-decoration:none;padding:6px;border-radius:8px
        }

        /* PRODUCT GRID */
        .grid-products{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); /* smaller cards */
    gap: 8px;
}



/* Grid product card */
..grid-products .product-card{
    padding: 8px;
    min-height: 200px;
    border-radius: 8px;
}



/* Grid image */
.grid-products .product-card img{
    width: 100%;
    height: 100px;
    object-fit: contain;
    border-radius: 6px;
    background: #f6f7f9;
    margin-bottom: 8px;
    padding:8px;
}

/* Grid title */
..grid-products .product-card h3{
    font-size: 13px;
    height: 32px;
    margin: 4px 0;
}

.product-slider{
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 20px;
    scroll-behavior: smooth;
}

/* Slider card */
.product-slider .product-card{
    min-width: 160px;
    padding: 15px;
    border-radius: 15px;
    background: white;
    border: 1px solid #ddd;
    text-align: center;
    box-shadow: var(--shadow);
}

/* Slider image */
.grid-products .product-card img{
    width: 100%;
    height: 140px;
    object-fit: contain;   /* IMPORTANT */
    background: #f6f7f9;
    border-radius: 6px;
    padding: 6px;
}


/* Slider title */
.product-slider .product-card h4{
    font-size: 15px;
    margin-top: 10px;
}
.price{
    color: #FF6B2C;
    font-weight: bold;
}

.product-card button{
    margin-top: 10px;
    width: 100%;
    padding: 8px 0;
    background: #00C853;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}


        .product-card h3{
            font-size:14px;margin:6px 0 8px;flex:0 0 auto;line-height:1.2;
            height:36px;overflow:hidden;word-break:break-word
        }

        .price{font-weight:800;color:var(--primary);font-size:14px}
        .old-price{font-size:12px;color:var(--muted);text-decoration:line-through;margin-left:6px}

        .space-between{display:flex;align-items:center;justify-content:space-between;margin-top:auto}

        .btn{
            display:inline-flex;align-items:center;
            padding:8px 12px;border-radius:10px;
            border:1px solid transparent;cursor:pointer;font-weight:700
        }

        .btn-primary{background:var(--primary);color:#fff}
        .btn-outline{background:#fff;border:1px solid #e6eef8;color:#0f172a}

        @media(max-width:980px){
            .layout{grid-template-columns:1fr}
            .sidebar{order:2}
            .search{display:none}
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                <svg viewBox="0 0 24 24" fill="none">
                    <path fill="#fff" d="M6 6h15l-1.5 9h-11z"/>
                    <circle cx="9" cy="19" r="1.5" fill="#fff"/>
                    <circle cx="17" cy="19" r="1.5" fill="#fff"/>
                    <path stroke="#fff" stroke-width="1.6" stroke-linecap="round" d="M4 4h2l1 10h11.5"/>
                </svg>
                QUICKKART
            </div>

            <div class="search">
                <input type="text" placeholder="Search within category">
                <button>Search</button>
            </div>

            <div class="nav-actions">
                <div class="nav-menu">
                    <a href="index.php">Home</a>
                </div>

                <button class="icon-btn">
                    <svg viewBox="0 0 24 24">
                        <path d="M7 4h-2l-1 2m0 0-1 2h17l-2 8h-12l-1-2m-1-8h17" stroke="white" stroke-width="1.6"/>
                    </svg>
                </button>

                <button class="icon-btn">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-6 1.67-6 3.75V20h12v-2.25C18 15.67 15.33 14 12 14z" fill="white"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="page">

        <div class="space-between" style="margin-bottom:12px;">
            <div>
                <p class="tag">Category</p>
                <h2 class="section-title"><?php echo htmlspecialchars($currentName ?? 'All Products'); ?></h2>
                <p class="muted">Handpicked quality, delivered fast.</p>
            </div>
            
        </div>

        <section class="layout">

            <!-- SIDEBAR -->
            <aside class="sidebar">
                <div class="card">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="index.php">All Products</a></li>

                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="category.php?id=<?php echo $cat['id']; ?>">
                                    <?php if (!empty($cat['image'])): ?>
                                        <img src="../assets/images/<?php echo htmlspecialchars($cat['image']); ?>" 
                                             style="width:26px;height:26px;object-fit:cover;border-radius:8px;">
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <!-- PRODUCTS -->
            <div class="content">
                <div class="grid-products">

                    <?php foreach ($products as $product): ?>
                        <div class="product-card">

                               <img loading="lazy" src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                                   alt="<?php echo htmlspecialchars($product['product_name']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=%27http://www.w3.org/2000/svg%27 width=%27200%27 height=%27140%27><rect width=%27200%27 height=%27140%27 fill=%27%23f6f7f9%27/><text x=%2710%27 y=%2780%27 font-size=%2714%27 fill=%27%23999%27>Image not available</text></svg>';">

                            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>

                            <div class="space-between">
                                <div>
                                    <span class="price">₹<?php echo number_format($product['price'],2); ?></span>
                                    <span class="old-price">₹<?php echo number_format($product['mrp'],2); ?></span>
                                </div>

                                <span class="tag">
                                    <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out'; ?>
                                </span>
                            </div>

                            <p class="muted">
                                <?php echo htmlspecialchars(substr($product['description'],0,80)); ?>...
                            </p>

                            <div class="space-between">
                                <a class="btn btn-outline" href="product.php?id=<?php echo $product['id']; ?>">View</a>

                                <form method="post" action="cart.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button class="btn btn-primary" type="submit">Add to Cart</button>
                                </form>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </section>

    </main>

</body>
</html>
