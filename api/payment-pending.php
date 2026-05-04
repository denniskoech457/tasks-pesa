<?php require_once 'auth.php'; require_login(); $ref=htmlspecialchars($_GET['reference'] ?? ''); ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Confirming Payment</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="auth-page"><div class="auth-card"><div class="auth-brand"><div class="logo">L</div><strong>LINKSPHERE AGENCIES</strong></div><h1>Confirming Payment</h1><p class="muted">Please complete the STK prompt on your phone. Your services will unlock immediately after MegaPay confirms your payment.</p><div style="display:flex;justify-content:center;margin:25px 0"><span class="spinner big-spinner"></span></div><p id="status" class="status">Waiting for payment confirmation...</p><a href="dashboard.php" class="primary-btn" style="display:block;text-align:center;margin-top:18px">Back to Dashboard</a></div></div>
<script>
const statusBox=document.getElementById('status');
const ref='<?php echo $ref; ?>';
async function checkPayment(){
  try{
    const res=await fetch('check-payment.php?reference='+encodeURIComponent(ref));
    const data=await res.json();
    statusBox.textContent=data.message || 'Checking payment...';
    statusBox.className = data.paid ? 'status ok' : (data.status==='failed' ? 'status error' : 'status');
    if(data.paid){ setTimeout(()=>{ window.location.href='dashboard.php'; },1000); }
  }catch(e){ statusBox.className='status error'; statusBox.textContent='Could not confirm yet. Still checking...'; }
}
checkPayment();
setInterval(checkPayment, 3000);
</script></body></html>
