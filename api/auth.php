<?php
header("Content-Type: text/html; charset=UTF-8");

function save_cookie($name, $value) {
    setcookie($name, $value, [
        "expires" => time() + (86400 * 30),
        "path" => "/",
        "secure" => true,
        "httponly" => false,
        "samesite" => "Lax"
    ]);
}

function delete_cookie($name) {
    setcookie($name, "", [
        "expires" => time() - 3600,
        "path" => "/",
        "secure" => true,
        "httponly" => false,
        "samesite" => "Lax"
    ]);
}

$action = $_POST['action'] ?? '';

if ($action === "signup") {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        header("Location: /signup.php?error=missing");
        exit;
    }

    save_cookie("user_name", $name);
    save_cookie("user_email", $email);
    save_cookie("user_password", $password);
    save_cookie("logged_in", "yes");
    save_cookie("paid", "no");

    header("Location: /payment-pending.php");
    exit;
}

if ($action === "signin") {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    $savedEmail = strtolower($_COOKIE['user_email'] ?? '');
    $savedPassword = $_COOKIE['user_password'] ?? '';

    if ($email === $savedEmail && $password === $savedPassword) {
        save_cookie("logged_in", "yes");

        if (($_COOKIE['paid'] ?? 'no') === "yes") {
            header("Location: /dashboard.php");
        } else {
            header("Location: /payment-pending.php");
        }
        exit;
    }

    header("Location: /signin.php?error=invalid");
    exit;
}

if ($action === "logout") {
    delete_cookie("logged_in");
    delete_cookie("user_name");
    delete_cookie("user_email");
    delete_cookie("user_password");
    delete_cookie("paid");

    header("Location: /signin.php");
    exit;
}

header("Location: /signin.php");
exit;
