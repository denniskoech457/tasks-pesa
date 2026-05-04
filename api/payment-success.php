<?php
require_once 'auth.php';
require_login();
if(has_paid()){
    setcookie('ls_paid','1',time()+60*60*24*30,'/');
}
header('Location: dashboard.php');
exit;
?>
