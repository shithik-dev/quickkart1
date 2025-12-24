# QUICKKART – Setup & Run Guide (Windows/XAMPP)

## Prerequisites
- XAMPP installed and Apache + MySQL running.
- PHP extensions: `pdo`, `pdo_mysql`, `openssl`, `json`, `mbstring` (enabled by default in XAMPP).
- A writable `assets/images/` folder for uploads.

## 1) Place the project
1. Copy the `quickkart` folder into your XAMPP web root (default: `C:\xampp\htdocs\quickkart`).
2. Ensure the structure matches:
   - `config/database.php`
   - `controllers/`, `models/`, `public/`, `assets/`, `api/`, `quickkart.sql`

## 2) Configure database connection
Open `config/database.php` and adjust credentials if needed:
```php
$host = "localhost";
$db_name = "quickkart";
$username = "root";
$password = ""; // default for XAMPP
```

## 3) Create database & tables
1. Start MySQL in XAMPP Control Panel.
2. Import schema:
   - Option A (phpMyAdmin): go to http://localhost/phpmyadmin → Import → choose `quickkart.sql` → Go.
   - Option B (CLI): `mysql -u root -p < quickkart.sql`

## 4) Start the servers
1. Start Apache and MySQL from XAMPP Control Panel.
2. Visit the site at: http://localhost/quickkart/public/index.php

## 5) Default access & admin
- Create a user via `public/signup.php`.
- First logged-in user (id=1) or any email starting with `admin@` gets admin access.
- Admin panel: http://localhost/quickkart/public/admin/dashboard.php

## 6) File/folder permissions
- Ensure `assets/images/` is writable to allow product/category image uploads.

## 7) Optional: API endpoints (JSON)
- `api/login.php` (POST email, password)
- `api/categories.php`
- `api/products.php?category_id=ID&q=search`
- `api/addToCart.php` (POST user_id, product_id, quantity)
- `api/cart.php?user_id=ID`
- `api/order.php` (POST user_id)

## 8) Cash on Delivery checkout
- Checkout uses COD only; no external payment config needed.

## 9) Troubleshooting
- Blank page: enable PHP errors in `php.ini` (`display_errors=On`) for local dev.
- DB errors: verify `config/database.php` credentials and that `quickkart` DB exists.
- 404s: ensure you access via `/public/*.php` paths or configure Apache DocumentRoot accordingly.

