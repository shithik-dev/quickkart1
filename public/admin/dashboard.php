<?php
require_once __DIR__ . '/../../controllers/AdminController.php';
$admin = new AdminController();
$stats = $admin->dashboardData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - QUICKKART</title>
    
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
            max-width: 1200px;
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 16px;
        }

        h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text);
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        /* Card Styles */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow);
            transform: translateY(-4px);
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card p {
            font-size: 48px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }

        /* Responsive */
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
                flex-wrap: wrap;
            }

            .nav-links a {
                font-size: 13px;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .card {
                padding: 20px;
            }

            .card p {
                font-size: 36px;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                gap: 12px;
            }

            .nav-links a {
                font-size: 11px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            h2 {
                font-size: 20px;
            }

            .card {
                padding: 16px;
            }

            .card h3 {
                font-size: 14px;
            }

            .card p {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
<header>
    <div>
        <div class="logo">Admin - QUICKKART</div>
        <div class="nav-links">
            <a href="products.php">Products</a>
            <a href="categories.php">Categories</a>
            <a href="orders.php">Orders</a>
            <a href="../logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
</header>
<div class="container">
    <h2>Dashboard</h2>
    <div class="grid">
        <div class="card">
            <h3>Total Products</h3>
            <p><?php echo $stats['products']; ?></p>
        </div>
        <div class="card">
            <h3>Categories</h3>
            <p><?php echo $stats['categories']; ?></p>
        </div>
        <div class="card">
            <h3>Total Orders</h3>
            <p><?php echo $stats['orders']; ?></p>
        </div>
    </div>
</div>
</body>
</html>

