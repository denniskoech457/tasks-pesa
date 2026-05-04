<?php
require_once 'auth.php';
require_login();
header('Content-Type: application/json');

$reference = trim($_GET['reference'] ?? '');
$email = current_email();
$payments = read_payments();

$payment = null;
if($reference !== '' && isset($payments[$reference]) && strtolower($payments[$reference]['email'] ?? '') === strtolower($email)){
    $payment = $payments[$reference] + ['reference'=>$reference];
} else {
    $payment = find_latest_payment_by_email($email);
}

if(!$payment){
    echo json_encode(['paid'=>false,'status'=>'none','message'=>'No payment request found.']); exit;
}

if(($payment['status'] ?? '') === 'paid'){
    setcookie('ls_paid','1',time()+60*60*24*30,'/');
    echo json_encode(['paid'=>true,'status'=>'paid','message'=>'Payment confirmed. Services unlocked.']); exit;
}

if(($payment['status'] ?? '') === 'failed'){
    echo json_encode(['paid'=>false,'status'=>'failed','message'=>'Payment failed. Please try again.']); exit;
}

echo json_encode(['paid'=>false,'status'=>'pending','message'=>'Waiting for payment confirmation...']);
?>
