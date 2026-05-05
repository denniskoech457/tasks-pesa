<?php
require_once 'auth.php';
require_login();
$user = htmlspecialchars(current_user() ?: 'User');
$transactionRequestId = htmlspecialchars($_GET['transaction_request_id'] ?? ($_COOKIE['transaction_request_id'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Verification</title>
<style>
:root{--primary:#2563eb;--secondary:#14b8a6;--text:#172033;--muted:#64748b;--border:#dbeafe}*{box-sizing:border-box}body{margin:0;font-family:'Segoe UI',Arial,sans-serif;background:linear-gradient(135deg,#eef6ff,#f7fffb);min-height:100vh;display:grid;place-items:center;color:var(--text);padding:18px}.card{width:100%;max-width:470px;background:#fff;border-radius:22px;padding:32px;text-align:center;box-shadow:0 20px 60px rgba(37,99,235,.15)}.logo{width:60px;height:60px;margin:0 auto 18px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;font-weight:900;font-size:24px}.spinner{width:54px;height:54px;margin:22px auto;border:5px solid #dbeafe;border-top-color:var(--primary);border-radius:50%;animation:spin .8s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}h1{margin:0 0 10px;font-size:26px}p{color:var(--muted);line-height:1.6}.status-box{margin-top:18px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:14px;font-weight:800}.success{background:#f0fdf4;border-color:#bbf7d0;color:#166534}.error{background:#fef2f2;border-color:#fecaca;color:#991b1b}.btn{display:inline-block;margin-top:18px;padding:13px 22px;border-radius:12px;background:var(--primary);color:#fff;text-decoration:none;font-weight:900}.small{font-size:12px;word-break:break-word;color:#94a3b8}
</style>
</head>
<body>
<div class="card">
  <div class="logo">L</div>
  <h1>Verifying Payment</h1>
  <p>Hello <?php echo $user; ?>, please complete the M-Pesa payment on your phone. We are checking MegaPay for confirmation.</p>
  <div class="spinner" id="spinner"></div>
  <div class="status-box" id="statusText">Waiting for MegaPay to verify payment...</div>
  <?php if($transactionRequestId): ?><p class="small">Transaction ID: <?php echo $transactionRequestId; ?></p><?php endif; ?>
  <p style="font-size:13px;margin-top:18px;">Do not close this page while verification is in progress.</p>
  <a href="/dashboard.php" class="btn">Back to Dashboard</a>
</div>
<script>
const transactionRequestId = "<?php echo $transactionRequestId; ?>";
const statusText = document.getElementById('statusText');
const spinner = document.getElementById('spinner');
let attempts = 0;
const maxAttempts = 60; // 5 minutes at 5 seconds
let timer = null;

async function checkPayment(){
  attempts++;
  if(!transactionRequestId){
    spinner.style.display='none';
    statusText.className='status-box error';
    statusText.textContent='Missing transaction request ID. Please go back and start payment again.';
    if(timer) clearInterval(timer);
    return;
  }
  try{
    const res = await fetch('/check-payment.php?transaction_request_id=' + encodeURIComponent(transactionRequestId), {cache:'no-store'});
    const data = await res.json();
    if(data.paid === true){
      spinner.style.display='none';
      statusText.className='status-box success';
      statusText.textContent='Payment verified successfully. Redirecting...';
      if(timer) clearInterval(timer);
      setTimeout(()=>{ window.location.href='/dashboard.php'; }, 1500);
      return;
    }
    statusText.className = data.status === 'failed' ? 'status-box error' : 'status-box';
    statusText.textContent = data.message || 'Waiting for payment confirmation...';
    if(data.status === 'failed'){
      spinner.style.display='none';
      if(timer) clearInterval(timer);
      return;
    }
    if(attempts >= maxAttempts){
      spinner.style.display='none';
      statusText.className='status-box error';
      statusText.textContent='Payment verification timed out. If you paid, refresh this page or contact support.';
      if(timer) clearInterval(timer);
    }
  }catch(e){
    statusText.textContent='Still checking payment status...';
  }
}
checkPayment();
timer = setInterval(checkPayment, 5000);
</script>
</body>
</html>
