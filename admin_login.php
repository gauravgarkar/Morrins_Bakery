<?php
session_start();

if (
    isset($_SESSION['admin_logged_in']) &&
    $_SESSION['admin_logged_in'] === true
) {
    header('Location: admin_orders.php');
    exit;
}

$errorMessage = '';

require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare(
        'SELECT id, username, password_hash
         FROM admin_users
         WHERE username = ?
         LIMIT 1'
    );

    if (!$stmt) {
        exit('Could not prepare the login check.');
    }

    $stmt->bind_param('s', $username);

    if (!$stmt->execute()) {
        $stmt->close();
        exit('Could not check the login details.');
    }

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = (int) $admin['id'];

        header('Location: admin_orders.php');
        exit;
    }

    $errorMessage = 'Incorrect username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Morrins</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <main class="order">
        <section class="order-now">
            <a class="brand-link" href="index.php">Morrins</a>
            <h1>Admin Login</h1>

            <?php if ($errorMessage !== ''): ?>
                <p role="alert">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="post" action="admin_login.php">
                <div class="form-field">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit">Log In</button>
            </form>
        </section>
    </main>
</body>
</html>