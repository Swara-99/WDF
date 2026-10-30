<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Login | StudentHub';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$pdo) {
        $error = 'Database connection unavailable. Import the database first.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['LAST_ACTIVITY'] = time();

            audit($pdo, (int)$user['id'], 'login', 'Successful login');
            header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'dashboard.php'));
            exit;
        } else {
            $error = 'Email or password is incorrect.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<main id="main-content">
    <section class="login-container">
        <div class="login-left">
            <h1>Welcome back.</h1>
            <h2>Your campus, connected.</h2>
            <p>Sign in to see your student dashboard, events and notices.</p>
        </div>
        
        <div class="login-right">
            <form class="login-form" method="post">
                <h2>Log in</h2>
                
                <?php if (isset($_GET['registered'])): ?>
                    <p class="success">Registration complete. You can now log in.</p>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <p class="alert" role="alert"><?= e($error) ?></p>
                <?php endif; ?>

                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <div class="field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" autocomplete="username" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                </div>

                <button class="btn" type="submit">Sign in</button>
                <p class="hint">New here? <a href="register.php">Create an account</a>.</p>
            </form>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>