<?php
require_once 'auth.php';
clear_app_cookie('ls_logged_in');
clear_app_cookie('ls_user');
clear_app_cookie('ls_email');
clear_app_cookie('ls_password');
clear_app_cookie('ls_paid');
clear_app_cookie('transaction_request_id');
header('Location: /signin.php');
exit;
?>
