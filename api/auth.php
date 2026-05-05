<?php
// Cookie-based auth helpers for Vercel/PHP serverless.
// This is for demo/prototype use. For production, use a database and hashed passwords.

function is_https_request(){
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function app_cookie($name, $value, $days = 30){
    setcookie($name, $value, [
        'expires' => time() + (86400 * $days),
        'path' => '/',
        'secure' => is_https_request(),
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    $_COOKIE[$name] = $value;
}

function clear_app_cookie($name){
    setcookie($name, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => is_https_request(),
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    unset($_COOKIE[$name]);
}

function current_user(){ return $_COOKIE['ls_user'] ?? ''; }
function current_email(){ return strtolower(trim($_COOKIE['ls_email'] ?? '')); }
function is_logged_in(){ return ($_COOKIE['ls_logged_in'] ?? '') === '1' && current_email() !== ''; }
function has_paid(){ return ($_COOKIE['ls_paid'] ?? '') === '1'; }

function require_login(){
    if(!is_logged_in()){
        header('Location: /signin.php');
        exit;
    }
}

function require_paid(){
    require_login();
    if(!has_paid()){
        header('Location: /dashboard.php?pay_required=1');
        exit;
    }
}

// Only process form actions when auth.php is called through POST.
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'] ?? '';

    if($action === 'signup'){
        $name = trim($_POST['name'] ?? ($_POST['full_name'] ?? ''));
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = trim($_POST['password'] ?? '');

        if($name === '' || $email === '' || $password === ''){
            header('Location: /signup.php?error=missing');
            exit;
        }

        app_cookie('ls_user', $name);
        app_cookie('ls_email', $email);
        app_cookie('ls_password', $password);
        app_cookie('ls_logged_in', '1');
        app_cookie('ls_paid', '0');

        header('Location: /dashboard.php');
        exit;
    }

    if($action === 'signin'){
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = trim($_POST['password'] ?? '');

        $savedEmail = strtolower(trim($_COOKIE['ls_email'] ?? ''));
        $savedPassword = $_COOKIE['ls_password'] ?? '';

        if($email !== '' && $password !== '' && $email === $savedEmail && $password === $savedPassword){
            app_cookie('ls_logged_in', '1');
            app_cookie('ls_email', $savedEmail);
            app_cookie('ls_user', $_COOKIE['ls_user'] ?? 'User');
            header('Location: /dashboard.php');
            exit;
        }

        header('Location: /signin.php?error=invalid');
        exit;
    }

    if($action === 'logout'){
        clear_app_cookie('ls_logged_in');
        clear_app_cookie('ls_user');
        clear_app_cookie('ls_email');
        clear_app_cookie('ls_password');
        clear_app_cookie('ls_paid');
        clear_app_cookie('transaction_request_id');
        header('Location: /signin.php');
        exit;
    }

    header('Location: /signin.php');
    exit;
}
?>
