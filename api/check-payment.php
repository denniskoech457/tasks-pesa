<?php
header('Content-Type: application/json');
error_reporting(0);
require_once 'config.php';
require_once 'auth.php';
require_login();

$transactionRequestId = trim($_GET['transaction_request_id'] ?? ($_COOKIE['transaction_request_id'] ?? ''));

if($transactionRequestId === ''){
    echo json_encode(['paid'=>false,'status'=>'missing','message'=>'Missing transaction request ID. Please start payment again.']);
    exit;
}

$payload = [
    'api_key' => MEGAPAY_API_KEY,
    'email' => MEGAPAY_EMAIL,
    'transaction_request_id' => $transactionRequestId
];

$ch = curl_init(MEGAPAY_STATUS_ENDPOINT);
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
    echo json_encode(['paid'=>false,'status'=>'checking','message'=>'Could not reach MegaPay. Still checking...']);
    exit;
}

$data = json_decode($response, true);
if(!is_array($data)){
    echo json_encode(['paid'=>false,'status'=>'checking','message'=>'Waiting for valid MegaPay response...','raw'=>$response]);
    exit;
}

$status = strtolower(trim($data['TransactionStatus'] ?? ''));
$code = (string)($data['TransactionCode'] ?? $data['ResultCode'] ?? '');
$desc = $data['ResultDesc'] ?? $data['ResponseDescription'] ?? 'Waiting for payment confirmation...';

if($status === 'completed' && $code === '0'){
    app_cookie('ls_paid', '1', 30);
    echo json_encode([
        'paid'=>true,
        'status'=>'paid',
        'message'=>'Payment verified successfully. Services unlocked.',
        'receipt'=>$data['TransactionReceipt'] ?? '',
        'raw'=>$data
    ]);
    exit;
}

// Known failed/cancelled responses
$failedCodes = ['1','1032','1037','1025','9999','2001','1019','1001'];
if(in_array($code, $failedCodes, true)){
    echo json_encode(['paid'=>false,'status'=>'failed','message'=>$desc ?: 'Payment failed or was cancelled.','raw'=>$data]);
    exit;
}

echo json_encode(['paid'=>false,'status'=>'pending','message'=>$desc ?: 'Waiting for payment confirmation...','raw'=>$data]);
exit;
?>
