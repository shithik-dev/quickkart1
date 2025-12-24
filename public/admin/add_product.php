<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
$admin = new AdminController();
$productController = new ProductController();
$categories = $productController->categories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['product_name'] ?? '');
    $category = intval($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $mrp = floatval($_POST['mrp'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $stock = intval($_POST['stock'] ?? 0);
    $imageName = '';

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
            $imageName = uniqid('prod_') . '.' . $ext;
            $dest = $uploadDir . $imageName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $errors[] = "Failed to upload image.";
                $imageName = '';
            }
        }
    } else {
        $errors[] = "Image is required.";
    }

    if (empty($errors)) {
        $productController->createProduct([
            'product_name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'category_id' => $category,
            'price' => $price,
            'mrp' => $mrp,
            'image' => $imageName,
            'description' => htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'),
            'stock' => $stock,
        ]);
        header("Location: products.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - QUICKKART</title>
    
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
            width: 100%;
            padding: 12px 18px;
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
            max-width: 700px;
            margin: 0 auto;
            padding: 40px 16px;
        }

        h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 28px;
        }

        /* Form Card */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            padding: 32px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Form Group */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Input Fields */
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: var(--transition);
            background: var(--bg);
            color: var(--text);
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--text-muted);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23005eff' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 36px;
        }

        /* Alert Styles */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
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

        /* File Input */
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 20px;
            background: var(--bg);
            border: 2px dashed var(--border);
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-muted);
            transition: var(--transition);
            text-align: center;
            min-height: 120px;
        }

        .file-input-wrapper input[type="file"]:hover ~ .file-input-label,
        .file-input-label:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .file-input-wrapper input[type="file"]:focus ~ .file-input-label {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        /* Form Row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Input Helper Text */
        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
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
            }

            .nav-links a {
                font-size: 13px;
            }

            .container {
                padding: 20px 12px;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .form-card {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            form {
                gap: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 16px 12px;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 16px;
            }

            .form-card {
                padding: 16px;
            }

            input[type="text"],
            input[type="number"],
            select,
            textarea {
                font-size: 16px;
            }

            .file-input-label {
                padding: 16px;
                min-height: 100px;
                font-size: 13px;
            }

            .btn {
                font-size: 13px;
                padding: 12px 16px;
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
    <h2>Add New Product</h2>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
    <?php endforeach; ?>

    <div class="form-card">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name *</label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price (₹) *</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="Enter selling price" required>
                </div>
                <div class="form-group">
                    <label for="mrp">MRP (₹) *</label>
                    <input type="number" id="mrp" name="mrp" step="0.01" placeholder="Enter MRP" required>
                </div>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity *</label>
                <input type="number" id="stock" name="stock" placeholder="Enter stock quantity" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter product description (optional)"></textarea>
                <span class="helper-text">Provide a detailed description of the product</span>
            </div>

            <div class="form-group">
                <label for="image">Product Image *</label>
                <div class="file-input-wrapper">
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                    <label for="image" class="file-input-label">
                        📸 Click to upload or drag and drop
                        <br>
                        <span style="font-size: 12px; font-weight: 500;">JPG, PNG, WEBP (Max 10MB)</span>
                    </label>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">✓ Save Product</button>
        </form>
    </div>
</div>
</body>
</html>

