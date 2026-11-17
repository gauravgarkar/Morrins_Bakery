<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Please submit the order form.');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if (
    $name === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone) ||
    $address === ''
) {
    exit('Enter your name, a valid email, a 10-digit mobile number, and your address.');
}


$items = [];

$allowedCakes = [
    'Chocolate Cake',
    'Butterscotch Cake',
    'Banana Cake',
    'Vanilla Cake',
    'Red Velvet Cake',
    'Ice Cream Cake'
];

$cake = trim($_POST['cake'] ?? '');

if ($cake !== '') {
    if (!in_array($cake, $allowedCakes, true)) {
        exit('Invalid cake selected.');
    }

    $cakeQuantity = filter_var(
        $_POST['cake_quantity'] ?? '',
        FILTER_VALIDATE_INT
    );

    if ($cakeQuantity === false || $cakeQuantity < 1) {
        exit('Enter a valid cake quantity.');
    }

    $items[] = [
        'product_name' => $cake,
        'quantity' => $cakeQuantity
    ];
}

$snackQuantityFields = [
    'Vegetable Puff' => 'qty_vegetable_puff',
    'Egg Puff' => 'qty_egg_puff',
    'Chicken Puff' => 'qty_chicken_puff',
    'Vegetable Samosa' => 'qty_vegetable_samosa',
    'Chicken Roll' => 'qty_chicken_roll',
    'Vegetable Cutlet' => 'qty_vegetable_cutlet',
    'Vegetable Sandwich' => 'qty_vegetable_sandwich',
    'Chicken Sandwich' => 'qty_chicken_sandwich',
    'Donut' => 'qty_donut',
    'Muffin' => 'qty_muffin',
    'Brownie' => 'qty_brownie',
    'Cookies' => 'qty_cookies'
];

$selectedSnacks = $_POST['snacks'] ?? [];

if (!is_array($selectedSnacks)) {
    exit('Invalid snack selection.');
}

foreach ($selectedSnacks as $snack) {
    if (!isset($snackQuantityFields[$snack])) {
        exit('Invalid snack selected.');
    }

    $quantityField = $snackQuantityFields[$snack];
    $quantity = filter_var($_POST[$quantityField] ?? '', FILTER_VALIDATE_INT);

    if ($quantity === false || $quantity < 1) {
        exit('Enter a valid quantity for each selected snack.');
    }

    $items[] = [
        'product_name' => $snack,
        'quantity' => $quantity
    ];
}

if (count($items) === 0) {
    exit('Please choose a cake or at least one snack.');
}
$conn->begin_transaction();

$orderStmt = $conn->prepare(
    'INSERT INTO orders (customer_name, email, phone, address)
     VALUES (?, ?, ?, ?)'
);

if (!$orderStmt) {
    $conn->rollback();
    exit('Could not prepare the order.');
}
$orderStmt->bind_param('ssss', $name, $email, $phone, $address);

if (!$orderStmt->execute()) {
    $conn->rollback();
    exit('Could not save the order.');
}

$orderId = $conn->insert_id;
$orderStmt->close();

$itemStmt = $conn->prepare(
    'INSERT INTO order_items (order_id, product_name, quantity)
     VALUES (?, ?, ?)'
);
if (!$itemStmt) {
    $conn->rollback();
    exit('Could not prepare the order items.');
}
foreach ($items as $item) {
    $productName = $item['product_name'];
    $quantity = $item['quantity'];

    $itemStmt->bind_param('isi', $orderId, $productName, $quantity);

    if (!$itemStmt->execute()) {
        $conn->rollback();
        exit('Could not save the order items.');
    }
}
$itemStmt->close();
$conn->commit();
$mailSettings = require __DIR__ . '/email_settings.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $mailSettings['username'];
$mail->Password = $mailSettings['app_password'];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom($mailSettings['username'], 'Morrins Bakery');
$mail->addAddress($email, $name);
$mail->Subject = 'Order confirmation - Morrins #' . $orderId;
$mail->isHTML(false);

$orderedProducts = [];

foreach ($items as $item) {
    $orderedProducts[] =
        $item['product_name'] . ' x ' . $item['quantity'];
}

$mail->Body =
    "Hello {$name},\n\n" .
    "Your order #{$orderId} has been placed.\n\n" .
    "Items:\n" . implode("\n", $orderedProducts) . "\n\n" .
    "Current status: Pending\n\n" .
    "Thank you,\nMorrins Bakery";

try {
    $mail->send();
    $emailStatus = 'A confirmation email was sent.';
} catch (Exception $e) {
    error_log('Order confirmation email failed: ' . $mail->ErrorInfo);
    $emailStatus = 'Your order was saved, but the email could not be sent.';
}

echo 'Order number: '
    . htmlspecialchars((string) $orderId, ENT_QUOTES, 'UTF-8')
    . '. '
    . htmlspecialchars($emailStatus, ENT_QUOTES, 'UTF-8');