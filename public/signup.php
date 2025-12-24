<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$auth = new AuthController();
$errors = $auth->signup();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - QUICKKART</title>
    
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
            background: linear-gradient(135deg, #0e7bff 0%, #0b5cff 50%, #0855ff 100%);
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
            right: -10%;
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
            left: -20%;
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
            max-width: 520px;
            background: var(--card-bg);
            padding: 40px 32px;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            z-index: 1;
            position: relative;
        }

        /* Auth Header */
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
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        textarea {
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: var(--transition);
            background: var(--bg);
            color: var(--text);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
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
            min-height: 80px;
            font-family: inherit;
        }

        /* Form Row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
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

        /* Footer Link */
        .auth-footer {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .auth-footer p {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 700;
        }

        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Password Strength Indicator */
        .password-note {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* Section Title */
        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin: 16px 0 8px 0;
            letter-spacing: 0.5px;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .section-title:first-of-type {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

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
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 24px 16px;
                border-radius: 16px;
            }

            .auth-title {
                font-size: 22px;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            textarea {
                font-size: 16px;
            }

            form {
                gap: 12px;
            }

            .section-title {
                margin-top: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-title">Create your account</div>
            <div class="auth-subtitle">Join QUICKKART to shop faster</div>
        </div>

        <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>

        <form method="post">
            <!-- Account Information -->
            <div class="section-title">Account Details</div>
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                    <span class="password-note">Min. 8 characters recommended</span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="section-title">Delivery Information</div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
            </div>

            

            <button class="btn btn-primary" type="submit">✓ Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account?</p>
            <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>

