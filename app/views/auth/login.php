<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asset Information System</title>
    <link rel="stylesheet" href="<?= BASEURL ?>css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h1>Asset Information System</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASEURL ?>auth/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="auth-link">
            Don't have an account? <a href="<?= BASEURL ?>auth/register">Register here</a>
        </div>
    </div>
</body>
</html>

