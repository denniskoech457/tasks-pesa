<?php
header('Content-Type: application/json');
error_reporting(0);

require_once 'config.php';

$phone = preg_replace('/\D/', '', $_POST['msisdn'] ?? '');

if ($phone === '') {
    echo json_encode([
        "success" => false,
        "message" => "Please enter your M-Pesa number."
    ]);
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
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode([
        "success" => false,
        "message" => "Connection error. Please try again."
    ]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['success']) || isset($data['transaction_request_id']) || isset($data['massage'])) {
    echo json_encode([
        "success" => true,
        "message" => "STK push sent successfully. Please complete payment on your phone.",
        "reference" => $reference
    ]);
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Failed to send STK push. Please try again.",
    "raw" => $data
]);
exit;
?>
