<?php
// MegaPay may call this webhook server-to-server.
// On Vercel/serverless we do not store callback results in files.
// Browser payment verification is handled through check-payment.php using MegaPay transactionstatus endpoint.
header('Content-Type: application/json');
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if(!is_array($input)) $input = $_POST;
echo json_encode(['ok'=>true,'message'=>'Callback received. Status verification is handled by transactionstatus endpoint.']);
?>
