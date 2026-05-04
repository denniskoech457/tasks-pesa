<?php require_once 'auth.php'; ?>
<form id="logoutForm" action="/auth.php" method="POST"><input type="hidden" name="action" value="logout"></form><script>document.getElementById('logoutForm').submit();</script>
