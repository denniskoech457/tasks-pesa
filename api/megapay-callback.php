<?php
require_once 'auth.php';
header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if(!is_array($input)) $input = $_POST;

$reference = $input['reference'] ?? $input['transaction_ref'] ?? $input['merchant_reference'] ?? $input['account_reference'] ?? '';
$status = strtolower($input['status'] ?? $input['payment_status'] ?? $input['result'] ?? $input['description'] ?? '');

if($reference === ''){
    echo json_encode(['ok'=>false,'message'=>'Missing reference']); exit;
}

$paidWords = ['success','successful','paid','complete','completed','confirmed'];
$failedWords = ['failed','cancelled','canceled','timeout','rejected'];

$newStatus = 'pending';
foreach($paidWords as $word){ if(strpos($status, $word) !== false){ $newStatus='paid'; break; } }
foreach($failedWords as $word){ if(strpos($status, $word) !== false){ $newStatus='failed'; break; } }

mark_payment($reference, [
    'status' => $newStatus,
    'callback_payload' => $input,
    'callback_raw' => $raw,
    'confirmed_at' => $newStatus === 'paid' ? date('c') : null
]);

echo json_encode(['ok'=>true,'reference'=>$reference,'status'=>$newStatus]);
?>
