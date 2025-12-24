<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$auth = new AuthController();
$errors = $auth->login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QUICKKART</title>
    
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
            --text: #0f172a;
            --text-muted: #64748b;
            --bg: #f7f9fc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 25px 60px rgba(0, 34, 94, 0.25);
            --radius: 14px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0b5cff 0%, #0e7bff 50%, #1596ff 100%);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--text);
            line-height: 1.6;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: var(--transition);
        }

        /* Auth Card */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            padding: 40px 32px;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            z-index: 1;
            position: relative;
        }

        /* Logo/Branding */
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 14px;
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

        /* Form */
        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 20px;
        }

        /* Form Group */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Input Fields */
        input[type="email"],
        input[type="password"] {
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: var(--transition);
            background: var(--bg);
            color: var(--text);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        input::placeholder {
            color: var(--text-muted);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            font-weight: 700;
            font-size: 15px;
            transition: var(--transition);
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border-color: transparent;
            width: 100%;
            padding: 12px 18px;
            box-shadow: 0 4px 12px rgba(0, 94, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 94, 255, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline {
            background: var(--card-bg);
            border-color: var(--border);
            color: var(--text);
            border-width: 1.5px;
        }

        .btn-outline:hover {
            background: var(--bg);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Auth Actions */
        .auth-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .forgot-link {
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .signup-link {
            flex: 1;
        }

        .signup-link .btn-outline {
            width: 100%;
        }

        /* Divider */
        .divider-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }

        .divider {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 28px 20px;
                border-radius: 16px;
            }

            .auth-title {
                font-size: 24px;
                margin-bottom: 6px;
            }

            .auth-subtitle {
                font-size: 14px;
            }

            .auth-header {
                margin-bottom: 24px;
            }

            input[type="email"],
            input[type="password"] {
                font-size: 16px;
            }

            .auth-actions {
                flex-direction: column;
                gap: 12px;
            }

            .forgot-link {
                width: 100%;
                text-align: center;
                padding: 10px 0;
            }

            .signup-link {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-title">Welcome back</div>
            <div class="auth-subtitle">Login to continue shopping</div>
        </div>

        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success">Account created successfully. Please login.</div>
        <?php endif; ?>

        <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button class="btn btn-primary" type="submit">Login to Your Account</button>
        </form>

        <div class="auth-actions">
            <a class="forgot-link" href="#">Forgot password?</a>
            <div class="signup-link">
                <a class="btn btn-outline" href="signup.php" style="display: flex; justify-content: center;">Create account</a>
            </div>
        </div>
    </div>
</body>
</html>

