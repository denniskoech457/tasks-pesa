<?php
header('Content-Type: application/json');
error_reporting(0);

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$phone = trim($_POST['msisdn'] ?? '');

if ($phone === '') {
    echo json_encode([
        "success" => false,
        "message" => "Please enter your M-Pesa number."
    ]);
    exit;
}

// Format phone number
$phone = preg_replace('/\D/', '', $phone);

if (substr($phone, 0, 1) === '0') {
    $phone = '254' . substr($phone, 1);
} elseif (substr($phone, 0, 1) === '7') {
    $phone = '254' . $phone;
}

if (!preg_match('/^2547\d{8}$/', $phone)) {
    echo json_encode([
        "success" => false,
        "message" => "Enter a valid Safaricom number."
    ]);
    exit;
}

$reference = 'LS-' . time() . '-' . rand(1000, 9999);

$payload = [
    "api_key"   => MEGAPAY_API_KEY,
    "email"     => MEGAPAY_EMAIL,
    "amount"    => ACTIVATION_AMOUNT,
    "msisdn"    => $phone,
    "reference" => $reference
];

$url = "https://megapay.co.ke/backend/v1/initiatestk";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError) {
    echo json_encode([
        "success" => false,
        "message" => "Connection error. Please try again."
    ]);
    exit;
}

$result = json_decode($response, true);

// STK was sent successfully
if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode([
        "success" => true,
        "message" => "STK push sent. Complete payment on your phone.",
        "reference" => $reference,
        "raw" => $result
    ]);
    exit;
}

// MegaPay returned an error
echo json_encode([
    "success" => false,
    "message" => $result['message'] ?? $result['description'] ?? "Payment request failed.",
    "raw" => $result
]);
exit;
?>
