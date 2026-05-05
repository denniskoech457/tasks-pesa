<?php
require_once 'auth.php';
require_login();

// Mark the browser session as paid/unlocked.
save_cookie('paid', 'yes');
save_cookie('ls_paid', '1');

header('Location: /dashboard.php');
exit;
?>
