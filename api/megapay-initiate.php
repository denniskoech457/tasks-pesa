<?php
header('Content-Type: application/json');
error_reporting(0);

require_once 'config.php';

$phone = preg_replace('/\D/', '', $_POST['msisdn'] ?? '');

if ($phone === '') {
    echo json_encode(["success" => false, "message" => "Enter phone number."]);
    exit;
}

if (substr($phone, 0, 1) === '0') {
    $phone = '254' . substr($phone, 1);
} elseif (substr($phone, 0, 1) === '7') {
    $phone = '254' . $phone;
}

$reference = 'LS' . time() . rand(100, 999);

$payload = [
    "api_key" => MEGAPAY_API_KEY,
    "email" => MEGAPAY_EMAIL,
    "amount" => ACTIVATION_AMOUNT,
    "msisdn" => $phone,
    "reference" => $reference
];

$ch = curl_init(MEGAPAY_INITIATE_ENDPOINT);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$transactionRequestId = $data['transaction_request_id'] ?? '';

if ($transactionRequestId !== '') {
    setcookie("transaction_request_id", $transactionRequestId, time() + 3600, "/");

    echo json_encode([
        "success" => true,
        "message" => "STK push sent. Complete payment on your phone.",
        "transaction_request_id" => $transactionRequestId
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => $data['massage'] ?? $data['message'] ?? "Failed to send STK push.",
    "raw" => $data
]);
exit;
?>
