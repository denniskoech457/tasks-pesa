<?php
require_once 'auth.php';
require_login();
header('Content-Type: application/json');

if (has_paid()) {
    echo json_encode(['paid' => true, 'status' => 'paid', 'message' => 'Payment confirmed. Services unlocked.']);
    exit;
}

echo json_encode(['paid' => false, 'status' => 'pending', 'message' => 'Waiting for payment confirmation...']);
exit;
?>
