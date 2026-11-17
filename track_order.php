<?php
require __DIR__ . '/config.php';

$order = null;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $phone = trim($_POST['phone'] ?? '');

    if (!$orderId || $orderId < 1 || !preg_match('/^[0-9]{10}$/', $phone)) {
        $errorMessage = 'Enter a valid order number and 10-digit phone number.';
    } else {
        $stmt = $conn->prepare(
            'SELECT id, status, created_at
             FROM orders
             WHERE id = ? AND phone = ?'
        );

        if (!$stmt) {
            exit('Could not look up the order.');
        }

        $stmt->bind_param('is', $orderId, $phone);
        $stmt->execute();

        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if (!$order) {
            $errorMessage = 'No order matched those details.';
        }

        $stmt->close();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order | Morrins</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <main class="order">
        <section class="order-now">
            <a class="brand-link" href="index.php">Morrins</a>
            <h1>Track Your Order</h1>
            <p>Enter your order number and the 10-digit phone number used when ordering.</p>

            <form method="post">
                <div class="form-field">
                    <label for="order_id">Order number</label>
                    <input type="number" id="order_id" name="order_id" min="1" required>
                </div>

                <div class="form-field">
                    <label for="phone">Phone number</label>
                    <input type="tel" id="phone" name="phone"
                           placeholder="Enter your 10-digit number" required>
                </div>

                <button type="submit" id="submit">Check Order Status</button>
            </form>
        
            <?php if ($errorMessage !== ''): ?>
                <p class="selection-message" role="alert">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <?php if ($order): ?>
                <section class="order-summary">
                    <h2>Order #<?= htmlspecialchars((string) $order['id'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p>
                        <strong>Status:</strong>
                        <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <p>
                        <strong>Order date:</strong>
                        <?= htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </section>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>