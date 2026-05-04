<?php
require_once 'config.php';
require_once 'auth.php';
require_login();
header('Content-Type: application/json');

$msisdn = preg_replace('/\D/', '', $_POST['msisdn'] ?? '');
if ($msisdn === '') { echo json_encode(['success'=>false,'message'=>'Please enter your M-Pesa number.']); exit; }

if (preg_match('/^0(7\d{8})$/',$msisdn,$m)) {
    $msisdn='254'.$m[1];
} elseif (preg_match('/^(7\d{8})$/',$msisdn,$m)) {
    $msisdn='254'.$m[1];
} elseif(!preg_match('/^2547\d{8}$/',$msisdn)){
    echo json_encode(['success'=>false,'message'=>'Use a valid Safaricom number like 07XXXXXXXX.']); exit;
}

$email = current_email();
$reference = 'ACT-' . time() . '-' . substr(md5($email . microtime(true)), 0, 8);

$payload = [
    'api_key' => MEGAPAY_API_KEY,
    'email' => MEGAPAY_EMAIL,
    'amount' => ACTIVATION_AMOUNT,
    'msisdn' => $msisdn,
    'reference' => $reference,
    // If MegaPay supports callback_url, keep this line. If not, it will be ignored by many APIs.
    'callback_url' => CALLBACK_URL
];

mark_payment($reference, [
    'email' => $email,
    'user' => current_user(),
    'msisdn' => $msisdn,
    'amount' => ACTIVATION_AMOUNT,
    'status' => 'pending',
    'created_at' => date('c')
]);

$ch = curl_init(MEGAPAY_ENDPOINT);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30
]);
$response = curl_exec($ch);
$error = curl_error($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($error){
    mark_payment($reference, ['status'=>'failed','gateway_error'=>$error]);
    echo json_encode(['success'=>false,'message'=>'cURL error: '.$error]); exit;
}

$data = json_decode($response, true);
mark_payment($reference, ['gateway_response'=>$data ?: $response, 'http_code'=>$http]);

if($http >= 200 && $http < 300){
    echo json_encode([
        'success'=>true,
        'reference'=>$reference,
        'message'=>'STK push sent. Complete payment on your phone. We will unlock your account after MegaPay confirms payment.'
    ]);
} else {
    mark_payment($reference, ['status'=>'failed']);
    echo json_encode(['success'=>false,'message'=>$data['message'] ?? $data['error'] ?? 'MegaPay request failed.','raw'=>$response]);
}
?>
