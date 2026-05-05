<?php
require_once 'config.php';
require_once 'auth.php';
require_login();

$user = htmlspecialchars(current_user() ?: 'User');
// Since this version only requests STK push and does not verify payment automatically,
// services remain locked until you manually set paid=yes later.
$paid = has_paid();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Task Pesa Dashboard</title>
<style>
:root{--primary:#2563eb;--secondary:#14b8a6;--accent:#f59e0b;--bg:#f6f9ff;--panel:#fff;--text:#172033;--muted:#64748b;--border:#dbeafe;--danger:#ef4444;--success:#16a34a}*{box-sizing:border-box}body{margin:0;font-family:'Segoe UI',Arial,sans-serif;background:linear-gradient(135deg,#eef6ff,#f7fffb);color:var(--text)}a{text-decoration:none;color:inherit}.app{display:flex;min-height:100vh}.sidebar{width:310px;background:#fff;border-right:1px solid var(--border);padding:24px 18px;position:fixed;top:0;bottom:0;overflow:auto;z-index:1000}.brand{display:flex;gap:12px;align-items:center;font-size:20px;color:var(--primary)}.logo{width:48px;height:48px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;font-weight:900;box-shadow:0 8px 25px rgba(37,99,235,.25)}.side-title{letter-spacing:6px;color:#94a3b8;font-weight:800;font-size:12px;margin:28px 0 12px}.side-link{display:flex;align-items:center;gap:12px;padding:12px;border-radius:14px;color:#24304f;font-weight:800;margin-bottom:8px}.side-link:hover,.side-link.active{background:#eef6ff}.side-link.locked-link{opacity:.58;cursor:not-allowed}.side-link span{width:42px;height:42px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;border-radius:12px;display:grid;place-items:center}.side-link b{margin-left:auto;background:#ccfbf1;color:#115e59;border-radius:8px;padding:4px 8px}.sidebar-overlay{display:none}.main{margin-left:310px;width:calc(100% - 310px)}.topbar{height:78px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:5}.menu-btn{display:none;border:0;background:var(--primary);color:#fff;border-radius:10px;padding:10px 14px;font-size:20px;cursor:pointer}.top-actions{display:flex;align-items:center;gap:15px}.balance{background:#eff6ff;padding:10px 15px;border-radius:14px;font-weight:800}.avatar{width:48px;height:48px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--accent));color:#fff;font-weight:900}.content{max-width:900px;padding:22px}.greeting,.payment-card,.info-card{background:#fff;border-radius:20px;padding:24px;box-shadow:0 12px 35px rgba(37,99,235,.09);margin-bottom:18px}.muted{color:var(--muted);line-height:1.6}.notice{border:1px solid #fed7aa;background:#fff7ed;color:#9a3412;padding:16px;border-radius:14px;font-weight:800;margin-bottom:16px}.success-notice{border:1px solid #bbf7d0;background:#f0fdf4;color:#166534;padding:16px;border-radius:14px;font-weight:800;margin-bottom:16px}.amount-card{display:flex;justify-content:space-between;align-items:center;background:#eff6ff;border:1px solid #bfdbfe;border-radius:16px;padding:22px;font-size:18px;margin-bottom:16px}.amount-card strong{font-size:28px}.info-card{display:flex;gap:18px}.icon{width:44px;height:44px;border-radius:12px;background:var(--primary);display:grid;place-items:center;color:#fff;flex:0 0 auto}.phone-row{display:flex;border:2px solid #dbeafe;border-radius:14px;overflow:hidden;margin-top:14px}.phone-row span{padding:16px;background:#f1f5f9;font-weight:900}.phone-row input{flex:1;border:0;padding:16px;font-weight:900;font-size:18px;outline:none;min-width:0}.primary-btn{width:100%;border:0;border-radius:16px;background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff;padding:18px;font-weight:900;font-size:17px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px}.primary-btn:disabled{opacity:.78;cursor:not-allowed}.locked,.status{color:#94a3b8;font-weight:700}.status{margin-top:14px}.ok{color:#15803d}.error{color:#dc2626}.spinner{width:18px;height:18px;border:3px solid #ffffff99;border-top-color:#fff;border-radius:50%;animation:spin .8s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}@media(max-width:850px){.sidebar{transform:translateX(-100%);transition:.25s ease;width:285px}.sidebar.show{transform:translateX(0)}.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:999}.sidebar-overlay.show{display:block}.main{margin-left:0;width:100%}.menu-btn{display:block}.topbar{padding:0 14px}.top-actions{gap:8px}.balance{font-size:13px;padding:8px 10px}.avatar{width:40px;height:40px}.content{padding:16px}.amount-card{align-items:flex-start;gap:8px;flex-direction:column}.amount-card strong{font-size:24px}.info-card{flex-direction:column}.phone-row span{padding:14px}.phone-row input{font-size:16px;padding:14px}.brand{font-size:18px}}
</style>
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo">T</div><strong>TASK PESA</strong></div>
  <p class="side-title">LIVE TASKS</p>
  <a href="<?php echo $paid?'/surveys.php':'#'; ?>" class="side-link <?php echo !$paid?'locked-link':''; ?>"><span>📋</span> Surveys <b>700</b></a>
  <a href="#" class="side-link locked-link"><span>✍️</span> Blogging <b>200</b></a>
  <a href="#" class="side-link locked-link"><span>🎬</span> Watch and Earn <b>50</b></a>
  <a href="<?php echo $paid?'/trivia.php':'#'; ?>" class="side-link <?php echo !$paid?'locked-link':''; ?>"><span>💡</span> Trivia <b>300</b></a>
  <a href="<?php echo $paid?'/chat.php':'#'; ?>" class="side-link <?php echo !$paid?'locked-link':''; ?>"><span>💬</span> Global Chat <b>AI</b></a>
  <p class="side-title">ACCOUNT</p>
  <a href="/dashboard.php" class="side-link active"><span>✅</span> Activate Account</a>
  <a href="#" class="side-link"><span>👤</span> Profile</a>
  <a href="/logout.php" class="side-link"><span>🚪</span> Sign Out</a>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<main class="main">
  <header class="topbar">
    <button id="menuBtn" class="menu-btn">☰</button>
    <div></div>
    <div class="top-actions"><div class="balance">Balance: <strong id="balanceText">KES 0</strong></div><div class="avatar"><?php echo strtoupper(substr($user,0,1)); ?></div></div>
  </header>
  <section class="content">
    <div class="greeting"><h1>Hello, <?php echo $user; ?> 👋</h1><p class="muted"><?php echo $paid?'Your account is activated. You can now access services.':'Please pay the activation fee to unlock Surveys, Blogging, Watch and Earn, Trivia and Global Chat.'; ?></p></div>
    <?php if($paid): ?><div class="success-notice">✅ Account activated. Services are now unlocked.</div><?php else: ?><div class="notice">⚠️ Pay activation fee first to unlock earning tasks.</div><?php endif; ?>
    <div class="amount-card"><span>● Activation Amount</span><strong>Ksh <?php echo ACTIVATION_AMOUNT; ?></strong></div>
    <?php if(!$paid): ?>
    <div class="info-card"><div class="icon">💳</div><div><h2>Amount Required: Ksh <?php echo ACTIVATION_AMOUNT; ?></h2><p class="muted">This is a one time fee payment to confirm that your account is being managed by a human being and not a robot. Our services are highly secured for human management only</p></div></div>
    <form id="activationForm" class="payment-card"><label><strong>M-Pesa Number</strong></label><div class="phone-row"><span>+254</span><input type="text" name="msisdn" id="msisdn" placeholder="7XXXXXXXX" required></div><p class="locked">🔒 STK prompt will be sent to this number</p><button class="primary-btn" type="submit" id="payBtn">🔐 Verify Your Account Now</button><p id="payStatus" class="status"></p></form>
    <?php else: ?><div class="payment-card"><h2>Unlocked Services</h2><p>Choose a service from the sidebar menu.</p></div><?php endif; ?>
  </section>
</main>
</div>
<script>
function getBalance(){return parseInt(localStorage.getItem('balance')||'0',10)}
function updateBalance(){document.querySelectorAll('#balanceText').forEach(el=>el.textContent='KES '+getBalance().toLocaleString())}
updateBalance();
const menuBtn=document.getElementById('menuBtn'), sidebar=document.getElementById('sidebar'), overlay=document.getElementById('sidebarOverlay');
if(menuBtn && sidebar && overlay){menuBtn.addEventListener('click',()=>{sidebar.classList.add('show');overlay.classList.add('show')});overlay.addEventListener('click',()=>{sidebar.classList.remove('show');overlay.classList.remove('show')});}
document.querySelectorAll('.locked-link').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();alert('Please pay the activation fee first to unlock this service.');}));
const form=document.getElementById('activationForm');
if(form){form.addEventListener('submit', async function(e){
  e.preventDefault();
  const btn=document.getElementById('payBtn');
  const status=document.getElementById('payStatus');
  btn.disabled=true; btn.innerHTML='<span class="spinner"></span> Sending STK Push...'; status.className='status'; status.textContent='';
  try{
    const res=await fetch('/megapay-initiate.php',{method:'POST',body:new FormData(this),cache:'no-store'});
    const text=await res.text();
    let data;
    try{ data=JSON.parse(text); }catch(err){ console.log('Raw response:',text); throw new Error('Invalid JSON response'); }
    if(data.success){ status.className='status ok'; status.textContent=data.message || 'STK push sent successfully. Complete payment on your phone.'; btn.innerHTML='STK Push Sent ✓'; }
    else{ status.className='status error'; status.textContent=data.message || 'Payment request failed.'; btn.disabled=false; btn.innerHTML='🔐 Verify Your Account Now'; }
  }catch(err){ console.error(err); status.className='status error'; status.textContent='Server error. Check your MegaPay API key/email and Vercel logs.'; btn.disabled=false; btn.innerHTML='🔐 Verify Your Account Now'; }
});}
</script>
</body>
</html>
