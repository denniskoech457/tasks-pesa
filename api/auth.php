<?php
function current_user(){ return $_COOKIE['ls_user'] ?? ''; }
function current_email(){ return strtolower(trim($_COOKIE['ls_email'] ?? '')); }
function is_logged_in(){ return current_user() !== ''; }

function data_dir(){
    $dir = __DIR__ . '/data';
    if(!is_dir($dir)) mkdir($dir, 0755, true);
    return $dir;
}

function payments_file(){ return data_dir() . '/payments.json'; }

function read_payments(){
    $file = payments_file();
    if(!file_exists($file)) return [];
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function write_payments($payments){
    file_put_contents(payments_file(), json_encode($payments, JSON_PRETTY_PRINT));
}

function find_latest_payment_by_email($email){
    $payments = read_payments();
    $latest = null;
    foreach($payments as $ref => $payment){
        if(strtolower($payment['email'] ?? '') === strtolower($email)){
            if(!$latest || strtotime($payment['created_at'] ?? '1970-01-01') > strtotime($latest['created_at'] ?? '1970-01-01')){
                $latest = $payment + ['reference' => $ref];
            }
        }
    }
    return $latest;
}

function mark_payment($reference, $data){
    $payments = read_payments();
    $existing = $payments[$reference] ?? [];
    $payments[$reference] = array_merge($existing, $data, ['updated_at' => date('c')]);
    write_payments($payments);
}

function has_paid(){
    if(($_COOKIE['ls_paid'] ?? '') === '1') return true;
    $email = current_email();
    if($email === '') return false;
    $latest = find_latest_payment_by_email($email);
    return $latest && (($latest['status'] ?? '') === 'paid');
}

function require_login(){ if(!is_logged_in()){ header('Location: /signin.php'); exit; } }
function require_paid(){ require_login(); if(!has_paid()){ header('Location: /dashboard.php?pay_required=1'); exit; } }
?>
