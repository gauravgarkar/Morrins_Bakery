<?php
session_start();

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true
) {
    header('Location: admin_login.php');
    exit;
}

require __DIR__ . '/config.php';
$allowedStatuses = [
    'Pending',
    'Preparing',
    'Ready',
    'Completed',
    'Cancelled'
];

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_status'])
) {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $newStatus = $_POST['status'] ?? '';

    if (
        !$orderId ||
        $orderId < 1 ||
        !in_array($newStatus, $allowedStatuses, true)
    ) {
        exit('Invalid order or status.');
    }

    $updateStmt = $conn->prepare(
        'UPDATE orders SET status = ? WHERE id = ?'
    );

    if (!$updateStmt) {
        exit('Could not prepare the status update.');
    }

    $updateStmt->bind_param('si', $newStatus, $orderId);

    if (!$updateStmt->execute()) {
        exit('Could not update the order status.');
    }

    $updateStmt->close();

    header('Location: admin_orders.php');
    exit;
}

$sql = "
    SELECT
        o.id,
        o.customer_name,
        o.email,
        o.phone,
        o.address,
        o.created_at,
        o.status,
        GROUP_CONCAT(
            CONCAT(oi.product_name, ' x ', oi.quantity)
            ORDER BY oi.id SEPARATOR ', '
        ) AS items
    FROM orders AS o
    LEFT JOIN order_items AS oi ON oi.order_id = o.id
    GROUP BY
        o.id, o.customer_name, o.email, o.phone, o.address,
        o.created_at, o.status
    ORDER BY o.created_at DESC
";

$result = $conn->query($sql);

if (!$result) {
    exit('Could not load orders.');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Bakery Orders</h1>
        <a class="logout-button" href="admin_logout.php">Log Out</a>
    </header>

    <main>
        <section>
            <h2>Customer Orders</h2>

            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['items'] ?? 'No items', ENT_QUOTES, 'UTF-8') ?></td>
    </tr>
<?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>