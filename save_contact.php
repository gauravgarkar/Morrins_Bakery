<?php
require __DIR__ . '/config.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone)) {
    exit('Enter a name, a valid email, and a 10-digit phone number.');
}

$stmt = $conn->prepare(
    'INSERT INTO contact (name, email, mobile) VALUES (?, ?, ?)'
);

if (!$stmt) {
    exit('SQL setup failed: ' . $conn->error);
}

$stmt->bind_param('sss', $name, $email, $phone);

if (!$stmt->execute()) {
    exit('Save failed: ' . $stmt->error);
}

header('Location: index.php?success=1#contact');
exit;