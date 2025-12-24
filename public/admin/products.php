<?php
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
$admin = new AdminController();
$productController = new ProductController();

if (isset($_GET['delete'])) {
    $productController->deleteProduct(intval($_GET['delete']));
    header("Location: products.php");
    exit;
}
$products = $admin->products();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products - QUICKKART</title>
    
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

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-edit {
            color: var(--primary);
            background: var(--primary-light);
            border-color: var(--primary-light);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-edit:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-delete {
            color: var(--danger);
            background: #fee2e2;
            border-color: #fee2e2;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-delete:hover {
            background: var(--danger);
            color: #fff;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 16px;
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
        }

        /* Table Styles */
        .table-wrapper {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
        }

        thead th {
            padding: 16px;
            text-align: left;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: var(--primary-light);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        td {
            padding: 16px;
            font-size: 14px;
        }

        td:first-child {
            font-weight: 600;
            color: var(--primary);
        }

        /* Stock Badge */
        .stock-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .stock-in {
            background: #dcfce7;
            color: #166534;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-out {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 8px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Price */
        .price {
            font-weight: 700;
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px;
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

            .table-wrapper {
                overflow-x: auto;
            }

            table {
                font-size: 12px;
                min-width: 600px;
            }

            th, td {
                padding: 10px 8px;
            }

            .btn-sm {
                padding: 5px 10px;
                font-size: 12px;
            }

            .actions {
                flex-direction: column;
                gap: 4px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 12px;
            }

            h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 12px;
                padding: 8px 14px;
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 8px 6px;
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
            <a href="categories.php">Categories</a>
            <a href="orders.php">Orders</a>
            <a href="../logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
</header>
<div class="container">
    <div class="header-section">
        <h2>Products</h2>
        <a class="btn btn-primary" href="add_product.php">+ Add Product</a>
    </div>
    
    <?php if (empty($products)): ?>
        <div class="table-wrapper">
            <div class="empty-state">
                <p>No products found. Create your first product to get started.</p>
                <a class="btn btn-primary" href="add_product.php">Add Your First Product</a>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td class="price">₹<?php echo number_format($product['price'], 2); ?></td>
                            <td>
                                <span class="stock-badge <?php echo $product['stock'] > 10 ? 'stock-in' : ($product['stock'] > 0 ? 'stock-low' : 'stock-out'); ?>">
                                    <?php echo $product['stock']; ?> units
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn-edit" href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                    <a class="btn-delete" href="products.php?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

