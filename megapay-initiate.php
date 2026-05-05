<?php
header('Content-Type: application/json');
error_reporting(0);
require_once 'config.php';
require_once 'auth.php';
require_login();

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success'=>false,'message'=>'Invalid request method.']);
    exit;
}

$msisdn = preg_replace('/\D/', '', $_POST['msisdn'] ?? '');
if($msisdn === ''){
    echo json_encode(['success'=>false,'message'=>'Please enter your M-Pesa number.']);
    exit;
}

if(preg_match('/^0(7\d{8})$/', $msisdn, $m)){
    $msisdn = '254' . $m[1];
} elseif(preg_match('/^(7\d{8})$/', $msisdn, $m)){
    $msisdn = '254' . $m[1];
} elseif(!preg_match('/^2547\d{8}$/', $msisdn)){
    echo json_encode(['success'=>false,'message'=>'Use a valid Safaricom number like 07XXXXXXXX.']);
    exit;
}

$reference = 'ACT' . time() . rand(1000, 9999);

$payload = [
    'api_key' => MEGAPAY_API_KEY,
    'email' => MEGAPAY_EMAIL,
    'amount' => (string) ACTIVATION_AMOUNT,
    'msisdn' => $msisdn,
    'reference' => $reference
];

if(defined('CALLBACK_URL') && CALLBACK_URL !== ''){
    $payload['callback_url'] = CALLBACK_URL;
}

$ch = curl_init(MEGAPAY_INITIATE_ENDPOINT);
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

if($error){
    echo json_encode(['success'=>false,'message'=>'Connection error. Please try again.','debug'=>$error]);
    exit;
}

$data = json_decode($response, true);
if(!is_array($data)){
    echo json_encode(['success'=>false,'message'=>'Invalid response from MegaPay.','raw'=>$response]);
    exit;
}

// MegaPay docs return this field after STK request.
$transactionRequestId = $data['transaction_request_id']
    ?? $data['TransactionID']
    ?? $data['transaction_id']
    ?? $data['request_id']
    ?? '';

$successCode = (string)($data['success'] ?? $data['Success'] ?? '');
$message = $data['massage'] ?? $data['message'] ?? 'STK push sent. Complete payment on your phone.';

if($transactionRequestId !== ''){
    app_cookie('transaction_request_id', $transactionRequestId, 1);
    app_cookie('transaction_reference', $reference, 1);

    echo json_encode([
        'success' => true,
        'message' => 'STK push sent. Complete payment on your phone.',
        'transaction_request_id' => $transactionRequestId,
        'reference' => $reference,
        'gateway_message' => $message
    ]);
    exit;
}

// Some gateways may return HTTP 200 without expected ID. Treat as error because we cannot verify without the ID.
echo json_encode([
    'success' => false,
    'message' => $message ?: 'STK request was sent but MegaPay did not return a transaction_request_id.',
    'raw' => $data,
    'http_code' => $http
]);
exit;
?>
