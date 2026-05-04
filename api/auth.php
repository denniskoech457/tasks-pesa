<?php
/*
  Cookie-based authentication for Vercel PHP.
  This file can be included by other PHP pages for helper functions.
  It only processes signup/signin/logout when a POST action is submitted.
*/

function is_https_request() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function save_cookie($name, $value, $days = 30) {
    setcookie($name, $value, [
        'expires' => time() + (86400 * $days),
        'path' => '/',
        'secure' => is_https_request(),
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    // make it available immediately in the current request too
    $_COOKIE[$name] = $value;
}

function delete_cookie($name) {
    setcookie($name, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => is_https_request(),
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    unset($_COOKIE[$name]);
}

function current_user() {
    return $_COOKIE['user_name'] ?? $_COOKIE['ls_user'] ?? '';
}

function current_email() {
    return strtolower(trim($_COOKIE['user_email'] ?? $_COOKIE['ls_email'] ?? ''));
}

function is_logged_in() {
    return (($_COOKIE['logged_in'] ?? '') === 'yes') || current_user() !== '';
}

function has_paid() {
    return (($_COOKIE['paid'] ?? '') === 'yes') || (($_COOKIE['ls_paid'] ?? '') === '1');
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /signin.php');
        exit;
    }
}

function require_paid() {
    require_login();
    if (!has_paid()) {
        header('Location: /dashboard.php?pay_required=1');
        exit;
    }
}

// These payment helpers are kept so other files do not break on Vercel.
// Vercel has a read-only filesystem, so this demo stores login in cookies only.
function read_payments() { return []; }
function write_payments($payments) { return false; }
function find_latest_payment_by_email($email) { return null; }
function mark_payment($reference, $data) { return false; }

$action = $_POST['action'] ?? '';

if ($action === 'signup') {
    $name = trim($_POST['name'] ?? $_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        header('Location: /signup.php?error=missing');
        exit;
    }

    // Store the account in browser cookies, as requested.
    save_cookie('user_name', $name);
    save_cookie('user_email', $email);
    save_cookie('user_password', $password);
    save_cookie('logged_in', 'yes');
    save_cookie('paid', 'no');

    // Backward-compatible names used by the existing pages.
    save_cookie('ls_user', $name);
    save_cookie('ls_email', $email);
    save_cookie('ls_paid', '0');

    header('Location: /dashboard.php');
    exit;
}

if ($action === 'signin') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    $savedEmail = strtolower(trim($_COOKIE['user_email'] ?? $_COOKIE['ls_email'] ?? ''));
    $savedPassword = $_COOKIE['user_password'] ?? '';

    if ($email !== '' && $password !== '' && $email === $savedEmail && $password === $savedPassword) {
        save_cookie('logged_in', 'yes');
        save_cookie('ls_email', $savedEmail);
        save_cookie('ls_user', current_user() ?: ($_COOKIE['user_name'] ?? 'User'));

        if (has_paid()) {
            header('Location: /dashboard.php');
        } else {
            header('Location: /dashboard.php');
        }
        exit;
    }

    header('Location: /signin.php?error=invalid');
    exit;
}

if ($action === 'logout') {
    foreach (['logged_in','user_name','user_email','user_password','paid','ls_user','ls_email','ls_paid'] as $cookie) {
        delete_cookie($cookie);
    }
    header('Location: /signin.php');
    exit;
}

// If this file is opened directly without an action, send user to sign in.
if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'auth.php' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /signin.php');
    exit;
}
?>
