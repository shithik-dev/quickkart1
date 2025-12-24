<?php
require_once __DIR__ . '/../controllers/ProductController.php';
session_start();
$productController = new ProductController();
$categories = $productController->categories();
$search = $_GET['q'] ?? null;
$products = $productController->products(null, $search);
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>QuickKart – Shop Fast</title>
    <style>
    /* ====== Base & Layout ====== */
    :root{--primary:#005eff;--primary-dark:#094acc;--bg:#f7f9fc;--card:#fff;--muted:#64748b;--radius:12px;--shadow:0 10px 30px rgba(15,23,42,0.08)}
        *{box-sizing:border-box}
        body{font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;background:var(--bg);color:#0f172a;margin:0}
        .navbar{background:linear-gradient(90deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 0;position:sticky;top:0;z-index:40}
        .navbar-inner{max-width:1200px;margin:0 auto;padding:0 16px;display:flex;align-items:center;gap:12px}
    .brand{font-weight:700;display:flex;align-items:center;gap:10px}
    .brand svg{width:28px;height:28px}
    .search{display:flex;gap:8px;align-items:center;flex:1;max-width:520px;margin:0 16px}
        .search input{flex:1;padding:8px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);color:#fff}
        .search button{background:rgba(255,255,255,0.12);color:#fff;border:1px solid transparent;padding:8px 12px;border-radius:8px;cursor:pointer}
        .nav-actions{display:flex;align-items:center;gap:12px}
    .nav-actions{display:flex;align-items:center;gap:8px}
    .nav-menu a{color:#fff;text-decoration:none;margin-right:8px}
    .icon-btn{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;background:rgba(255,255,255,0.12);border-radius:8px;color:#fff;text-decoration:none}
    .nav-mobile-toggle{display:none;background:transparent;border:none;color:#fff}
    .nav-drawer{display:none}

    /* ====== Banner Slider ====== */
    .banner{position:relative;border-radius:10px;overflow:hidden;background:#000;color:#fff}
    .banner .slide{display:none;position:relative}
    .banner .slide.active{display:block}
    .banner img{width:100%;height:420px;object-fit:cover;display:block}
    .banner .overlay{position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,0.15), rgba(0,0,0,0.45))}
    .banner .content{position:absolute;left:28px;bottom:28px;z-index:3;max-width:60%}
    .banner .tag{background:rgba(255,255,255,0.12);display:inline-block;padding:6px 10px;border-radius:999px;margin:0 0 8px;color:#fff}
    .banner h1{font-size:28px;margin:6px 0}
    .banner p{margin:6px 0;color:rgba(255,255,255,0.9)}
    .dots{position:absolute;left:50%;transform:translateX(-50%);bottom:12px;display:flex;gap:8px;z-index:4}
    .dots button{width:10px;height:10px;border-radius:50%;border:none;background:rgba(255,255,255,0.5);cursor:pointer}
    .dots button.active{background:#fff;box-shadow:0 0 6px rgba(0,0,0,0.4)}

    /* ====== Layout & Sidebar ====== */
    .layout{display:flex;gap:16px}
    .sidebar{width:240px}
    .card{background:var(--card);border-radius:10px;padding:12px}
    .sidebar ul{list-style:none;padding:0;margin:8px 0}
    .sidebar li{margin:6px 0}
    .sidebar a{display:flex;align-items:center;gap:8px;text-decoration:none;color:#222}

    /* ====== Product Grid ====== */
    .content{flex:1}
    .grid-products{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px}
    .product-card{background:var(--card);padding:12px;border-radius:10px}
    .product-card img{width:100%;height:180px;object-fit:cover;border-radius:8px;background:#f3f4f6;display:block;min-height:120px}
    .product-card h3{margin:8px 0 6px;font-size:16px}
    .price{font-weight:700;color:var(--accent);margin-right:8px}
    .old-price{color:var(--muted);text-decoration:line-through;margin-left:6px;font-size:0.9rem}
    .tag{background:#eef5ff;color:var(--accent);padding:4px 8px;border-radius:6px;font-size:0.85rem}
    .muted{color:var(--muted);font-size:0.9rem}
    .product-slider {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 20px;
}
.product-card {
    min-width: 160px;
    padding: 15px;
    border-radius: 15px;
    flex-direction:column;
    display:flex;
    align-items:center;

    background: white;
    border: 1px solid #ddd;
    text-align: center;
}
.product-card img {
     display: block;
    margin: 0 auto 10px auto; /* centers image */
    object-fit: contain;
    width: 120px;
    height: 120px;
    object-fit: cover;
}
.product-card h4 {
    font-size: 15px;
    margin-top: 10px;
}
.price {
    color: #FF6B2C;
    font-weight: bold;
}
.product-card button {
    margin-top: 10px;
    width: 100%;
    padding: 8px 0;
    background: blue;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
}

    /* ====== Buttons & Footer ====== */
    .btn{border:0;padding:8px 12px;border-radius:8px;cursor:pointer}
    .btn-primary{background:var(--accent);color:#fff}
    .btn-outline{background:transparent;border:1px solid rgba(0,0,0,0.08);color:#222}
    footer{max-width:1200px;margin:24px auto;text-align:center;color:var(--muted);font-size:0.9rem}

    /* ====== Responsive ====== */
    @media (max-width:900px){.banner img{height:320px}.sidebar{display:none}.nav-mobile-toggle{display:inline-block}}
    @media (max-width:600px){.banner img{height:220px}.banner .content{max-width:90%;left:12px;bottom:12px}}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="brand">
                <svg viewBox="0 0 24 24" fill="none"><path fill="#fff" d="M6 6h15l-1.5 9h-11z"/><circle cx="9" cy="19" r="1.5" fill="#fff"/><circle cx="17" cy="19" r="1.5" fill="#fff"/><path stroke="#fff" stroke-width="1.6" stroke-linecap="round" d="M4 4h2l1 10h11.5"/></svg>
                QUICKKART
            </div>
            <form class="search" method="get" action="index.php">
                <input type="text" name="q" placeholder="Search for fresh products..." value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
                <button type="submit">Search</button>
            </form>
            <div class="nav-actions">
                <div class="nav-menu">
                    <a href="index.php">Home</a>
                    <a href="category.php">Categories</a>
                    <a href="orders.php">Orders</a>
                </div>
                <a class="icon-btn" aria-label="Cart" href="cart.php">
                    <svg viewBox="0 0 24 24"><path d="M7 4h-2l-1 2m0 0-1 2h17l-2 8h-12l-1-2m-1-8h17" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a class="icon-btn" aria-label="Logout" href="logout.php">
                        <svg viewBox="0 0 24 24"><path d="M16 17l5-5-5-5M21 12H9m4 9H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                <?php else: ?>
                    <a class="icon-btn" aria-label="Login" href="login.php">
                        <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-6 1.67-6 3.75V20h12v-2.25C18 15.67 15.33 14 12 14z" fill="white"/></svg>
                    </a>
                <?php endif; ?>
                <button class="nav-mobile-toggle" aria-label="Toggle menu">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>
        <div class="nav-drawer">
            <form class="search" method="get" action="index.php">
                <input type="text" name="q" placeholder="Search products" value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
                <button type="submit">Search</button>
            </form>
            <div class="nav-menu">
                <a href="index.php">Home</a>
                <a href="category.php">Categories</a>
                <a href="orders.php">Orders</a>
            </div>
        </div>
    </nav>

    <main class="page">
        <!-- Banner slider -->
        <section class="banner" data-slider>
            <div class="slide active">
                <img src="../assets/images/banner1.jpg" alt="Fresh groceries">
                <div class="overlay"></div>
                <div class="content">
                    <p class="tag">Fresh & Fast</p>
                    <h1>Groceries delivered in minutes.</h1>
                    <p>Quality fruits, veggies, snacks, and essentials with quick delivery.</p>
                    <button class="btn btn-primary">Shop Now</button>
                </div>
            </div>
            <div class="slide">
                <img src="../assets/images/banner2.jpg" alt="Daily deals">
                <div class="overlay"></div>
                <div class="content">
                    <p class="tag">Daily Deals</p>
                    <h1>Save big on your weekly essentials.</h1>
                    <p>Exclusive offers on top brands—limited-time discounts every day.</p>
                    <button class="btn btn-primary">View Deals</button>
                </div>
            </div>
            <div class="slide">
                <img src="../assets/images/banner3.jpg" alt="Organic picks">
                <div class="overlay"></div>
                    <div class="content">
                        <p class="tag">Organic Picks</p>
                        <h1>Healthy. Organic. Sustainably sourced.</h1>
                        <p>Handpicked fresh produce for a healthier lifestyle.</p>
                        <button class="btn btn-primary">Explore</button>
                    </div>
            </div>
            <div class="dots"></div>
        </section>

        <!-- Layout with sidebar -->
        <section class="layout" style="margin-top:24px;">
            <aside class="sidebar" data-sidebar>
                <div class="card">
                    <div class="space-between">
                        <h4>Categories</h4>
                        <button class="btn btn-outline" data-sidebar-toggle style="display:none;">Toggle</button>
                    </div>
                    <ul>
                        <li><a href="index.php"><span>All Products</span></a></li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="category.php?id=<?php echo $cat['id']; ?>">
                                <?php if (!empty($cat['image'])): ?>
                                    <img src="../assets/images/<?php echo htmlspecialchars($cat['image']); ?>" alt="" style="width:26px;height:26px;object-fit:cover;border-radius:8px;">
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($cat['category_name']); ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <div class="content">
                <div class="space-between" style="margin-bottom:12px;">
                    <h2 class="section-title">Featured Products</h2>
                    <a class="btn btn-outline" href="#">View all</a>
                </div>
                <div class="grid-products">
                    <!-- Sample product cards -->
                    <?php foreach ($products as $product): ?>
                    <div class="card product-card">
                        <img loading="lazy" src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=%27http://www.w3.org/2000/svg%27 width=%27200%27 height=%27140%27><rect width=%27200%27 height=%27140%27 fill=%27%23f3f4f6%27/><text x=%2710%27 y=%2780%27 font-size=%2714%27 fill=%27%23999%27>Image not available</text></svg>';">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <div class="flex" style="justify-content:space-between;">
                            <div>
                                <span class="price">₹<?php echo number_format($product['price'],2); ?></span>
                                <span class="old-price">₹<?php echo number_format($product['mrp'],2); ?></span>
                            </div>
                            <span class="tag"><?php echo $product['stock'] > 0 ? 'In Stock' : 'Out'; ?></span>
                        </div>
                        <p class="muted"><?php echo htmlspecialchars(substr($product['description'],0,80)); ?>...</p>
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

    <footer>
        © <?php echo date('Y'); ?> QUICKKART. Cash on Delivery available. Crafted with care.
    </footer>
        <script>
        // Inline slider + navbar toggle
        (function(){
            // Slider
            const banner = document.querySelector('.banner');
            if(banner){
                const slides = Array.from(banner.querySelectorAll('.slide'));
                const dotsContainer = banner.querySelector('.dots');
                let idx = 0, timer = null;
                // create dots
                slides.forEach((s,i)=>{
                    const btn = document.createElement('button');
                    btn.dataset.index = i; btn.type='button';
                    if(i===0) btn.classList.add('active');
                    btn.addEventListener('click', e=>{ goTo(parseInt(e.currentTarget.dataset.index)); reset(); });
                    dotsContainer.appendChild(btn);
                });
                const dots = Array.from(dotsContainer.children);
                function show(i){ slides.forEach((s,si)=> s.classList.toggle('active', si===i)); dots.forEach((d,di)=> d.classList.toggle('active', di===i)); idx=i; }
                function next(){ show((idx+1)%slides.length); }
                function goTo(i){ show(i); }
                function reset(){ clearInterval(timer); timer = setInterval(next,5000); }
                // start
                show(0); reset();
            }

            // Nav mobile toggle
            const toggle = document.querySelector('.nav-mobile-toggle');
            const drawer = document.querySelector('.nav-drawer');
            if(toggle && drawer){
                toggle.addEventListener('click', ()=>{ drawer.style.display = drawer.style.display === 'block' ? 'none' : 'block'; });
            }
        })();
        </script>
</body>
</html>

