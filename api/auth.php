<?php

// Always start clean
header("Content-Type: text/html; charset=UTF-8");

// Get action
$action = $_POST['action'] ?? '';

// =========================
// SIGN UP
// =========================
if ($action === 'signup') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation
    if ($name === '' || $email === '' || $password === '') {
        header("Location: /signup.php?error=missing");
        exit;
    }

    // Save in cookies (DEMO PURPOSE ONLY)
    setcookie("user_name", $name, time() + (86400 * 30), "/");
    setcookie("user_email", $email, time() + (86400 * 30), "/");
    setcookie("user_password", $password, time() + (86400 * 30), "/");
    setcookie("logged_in", "yes", time() + (86400 * 30), "/");

    // Default payment status
    setcookie("paid", "no", time() + (86400 * 30), "/");

    // Redirect to payment page
    header("Location: /payment-pending.php");
    exit;
}


// =========================
// SIGN IN
// =========================
if ($action === 'signin') {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $savedEmail = $_COOKIE['user_email'] ?? '';
    $savedPassword = $_COOKIE['user_password'] ?? '';

    // Check credentials
    if ($email === $savedEmail && $password === $savedPassword) {

        setcookie("logged_in", "yes", time() + (86400 * 30), "/");

        // Check payment status
        if (($_COOKIE['paid'] ?? '') === 'yes') {
            header("Location: /dashboard.php");
        } else {
            header("Location: /payment-pending.php");
        }
        exit;
    }

    // Invalid login
    header("Location: /signin.php?error=invalid");
    exit;
}


// =========================
// LOGOUT
// =========================
if ($action === 'logout') {

    // Clear cookies
    setcookie("logged_in", "", time() - 3600, "/");
    setcookie("user_name", "", time() - 3600, "/");
    setcookie("user_email", "", time() - 3600, "/");
    setcookie("user_password", "", time() - 3600, "/");
    setcookie("paid", "", time() - 3600, "/");

    header("Location: /signin.php");
    exit;
}


// =========================
// DEFAULT FALLBACK
// =========================
header("Location: /signin.php");
exit;

?>
