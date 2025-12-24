<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
$admin = new AdminController();
$productController = new ProductController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['category_name'] ?? '');
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid image type.";
        } else {
            $uploadDir = __DIR__ . '/../../assets/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $image = uniqid('cat_') . '.' . $ext;
            $target = $uploadDir . $image;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = "Failed to upload image.";
                $image = '';
            }
        }
    }
    if (empty($errors) && $name) {
        $productController->createCategory(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $image);
        header("Location: categories.php");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $productController->deleteCategory(intval($_GET['delete']));
    header("Location: categories.php");
    exit;
}

$categories = $productController->categories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories - QUICKKART</title>
    
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

        h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 30px;
        }

        /* Form Styles */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            margin-bottom: 32px;
        }

        .form-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text);
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 12px;
            align-items: flex-end;
        }

        .form-group input {
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            transition: var(--transition);
            background: var(--bg);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-label {
            display: inline-block;
            padding: 12px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            transition: var(--transition);
            width: 100%;
            text-align: center;
        }

        .file-input-wrapper input[type="file"]:hover ~ .file-input-label,
        .file-input-label:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        /* Alert Styles */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
            border-left: 4px solid;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left-color: var(--success);
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

        /* Category Image Preview */
        .category-image {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            overflow: hidden;
            text-align: center;
            line-height: 40px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        /* Responsive */
        @media (max-width: 1024px) {
            .form-group {
                grid-template-columns: 1fr;
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

            .form-card {
                padding: 20px;
            }

            .form-group {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            table {
                font-size: 12px;
                min-width: 500px;
            }

            th, td {
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px 12px;
            }

            h2 {
                font-size: 20px;
            }

            .form-card {
                padding: 16px;
            }

            .btn {
                font-size: 12px;
                padding: 8px 12px;
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 8px;
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
            <a href="orders.php">Orders</a>
            <a href="../logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
</header>
<div class="container">
    <h2>Categories</h2>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
    <?php endforeach; ?>

    <div class="form-card">
        <h3>Add New Category</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="category_name" placeholder="Enter category name" required>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="categoryImage" accept=".jpg,.jpeg,.png,.webp">
                    <label for="categoryImage" class="file-input-label">📁 Choose Image</label>
                </div>
                <button class="btn btn-primary" type="submit">Add Category</button>
            </form>
        </form>
    </div>

    <?php if (empty($categories)): ?>
        <div class="table-wrapper">
            <div class="empty-state">
                <p>No categories found yet. Create your first category to get started.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                            <td>
                                <?php if (!empty($cat['image'])): ?>
                                    <div class="category-image">
                                        <img src="../../assets/images/<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['category_name']); ?>">
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 12px;">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn-delete" href="categories.php?delete=<?php echo $cat['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
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

