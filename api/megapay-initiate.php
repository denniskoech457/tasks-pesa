<?php
header('Content-Type: application/json');
error_reporting(0);
require_once 'config.php';
require_once 'auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'message'=>'Invalid request method.']);
    exit;
}

$msisdn = preg_replace('/\D/', '', $_POST['msisdn'] ?? '');
if ($msisdn === '') {
    echo json_encode(['success'=>false,'message'=>'Please enter your M-Pesa number.']);
    exit;
}

if (preg_match('/^0(7\d{8})$/', $msisdn, $m)) {
    $msisdn = '254' . $m[1];
} elseif (preg_match('/^(7\d{8})$/', $msisdn, $m)) {
    $msisdn = '254' . $m[1];
} elseif (!preg_match('/^2547\d{8}$/', $msisdn)) {
    echo json_encode(['success'=>false,'message'=>'Use a valid Safaricom number like 07XXXXXXXX or 7XXXXXXXX.']);
    exit;
}

$reference = 'TP-' . time() . '-' . rand(1000, 9999);

$payload = [
    'api_key' => MEGAPAY_API_KEY,
    'email' => MEGAPAY_EMAIL,
    'amount' => ACTIVATION_AMOUNT,
    'msisdn' => $msisdn,
    'reference' => $reference
];

$endpoint = defined('MEGAPAY_ENDPOINT') ? MEGAPAY_ENDPOINT : 'https://megapay.co.ke/backend/v1/initiatestk';

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30
]);
$response = curl_exec($ch);
$error = curl_error($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($error) {
    echo json_encode(['success'=>false,'message'=>'Connection error: '.$error]);
    exit;
}

$data = json_decode($response, true);

// MegaPay documentation returns HTTP 200 with success="200" and transaction_request_id when STK request is accepted.
$accepted = ($http >= 200 && $http < 300) && (
    (isset($data['success']) && (string)$data['success'] === '200') ||
    !empty($data['transaction_request_id']) ||
    stripos((string)($data['massage'] ?? $data['message'] ?? ''), 'sent') !== false
);

if ($accepted) {
    echo json_encode([
        'success' => true,
        'message' => 'STK push sent successfully. Please complete payment on your phone.',
        'reference' => $reference,
        'transaction_request_id' => $data['transaction_request_id'] ?? ''
    ]);
    exit;
}

echo json_encode([
    'success' => false,
    'message' => $data['massage'] ?? $data['message'] ?? $data['error'] ?? 'Failed to send STK push. Please confirm MegaPay API key and email.',
    'http_code' => $http,
    'raw' => $data ?: $response
]);
exit;
?>
