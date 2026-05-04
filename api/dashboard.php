<?php
require_once 'config.php';

// Check login
if (($_COOKIE['logged_in'] ?? '') !== 'yes') {
    header("Location: /signin.php");
    exit;
}

// Get user + payment status
$user = htmlspecialchars($_COOKIE['user_name'] ?? 'User');
$paid = (($_COOKIE['paid'] ?? 'no') === 'yes');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<style>
/* === YOUR FULL CSS (UNCHANGED) === */
body{margin:0;font-family:'Segoe UI',Arial,sans-serif;background:#f6f9ff}
.sidebar{width:280px;position:fixed;height:100%;background:#fff;border-right:1px solid #ddd;padding:20px}
.main{margin-left:280px}
.topbar{height:70px;background:#fff;border-bottom:1px solid #ddd;display:flex;align-items:center;justify-content:space-between;padding:0 20px}
.content{padding:20px}
.primary-btn{background:#2563eb;color:#fff;border:none;padding:15px;border-radius:10px;width:100%;cursor:pointer}
.phone-row{display:flex;border:1px solid #ddd;border-radius:10px;overflow:hidden}
.phone-row span{padding:15px;background:#eee}
.phone-row input{flex:1;border:none;padding:15px}
.notice{background:#fff7ed;padding:15px;border-radius:10px;margin-bottom:10px}
.success-notice{background:#f0fdf4;padding:15px;border-radius:10px;margin-bottom:10px}
.spinner{width:18px;height:18px;border:3px solid #fff;border-top-color:#000;border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
</style>

</head>

<body>

<div class="sidebar">
    <h3>LINKSPHERE</h3>

    <p><b>Tasks</b></p>

    <a href="<?php echo $paid ? '/surveys.php' : '#'; ?>">Surveys (700)</a><br>
    <a href="<?php echo $paid ? '/trivia.php' : '#'; ?>">Trivia (300)</a><br>
    <a href="<?php echo $paid ? '/chat.php' : '#'; ?>">Global Chat</a><br>

    <p><b>Account</b></p>
    <a href="/dashboard.php">Dashboard</a><br>
    <a href="/logout.php">Logout</a>
</div>

<div class="main">

<div class="topbar">
    <div>Welcome, <?php echo $user; ?></div>
    <div>Balance: KES <span id="balanceText">0</span></div>
</div>

<div class="content">

<h2>Hello, <?php echo $user; ?> 👋</h2>

<?php if($paid): ?>
<div class="success-notice">
✅ Account verified. All services unlocked.
</div>
<?php else: ?>
<div class="notice">
⚠️ Please activate your account to access services
</div>
<?php endif; ?>

<h3>Activation Fee: KES <?php echo ACTIVATION_AMOUNT; ?></h3>

<?php if(!$paid): ?>

<form id="activationForm">
    <label>Phone Number</label>

    <div class="phone-row">
        <span>+254</span>
        <input type="text" name="msisdn" placeholder="7XXXXXXXX" required>
    </div>

    <br>

    <button class="primary-btn" id="payBtn">Verify Account</button>

    <p id="status"></p>
</form>

<?php else: ?>

<p>You can now access Surveys, Trivia and Chat.</p>

<?php endif; ?>

</div>
</div>

<script>

// Balance (local demo)
function getBalance(){
    return parseInt(localStorage.getItem('balance') || '0');
}
document.getElementById('balanceText').innerText = getBalance();

// Payment
const form = document.getElementById('activationForm');

if(form){
    form.addEventListener('submit', async function(e){
        e.preventDefault();

        const btn = document.getElementById('payBtn');
        const status = document.getElementById('status');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Sending...';

        try{
            const res = await fetch('/megapay-initiate.php',{
                method:'POST',
                body:new FormData(this)
            });

            const data = await res.json();

            status.innerText = data.message || "Request sent";

            if(data.success){
                setTimeout(()=>{
                    window.location.href = '/payment-pending.php';
                },1500);
            }

        }catch(err){
            status.innerText = "Error sending request";
        }

        btn.disabled = false;
        btn.innerText = "Verify Account";

    });
}

</script>

</body>
</html>
