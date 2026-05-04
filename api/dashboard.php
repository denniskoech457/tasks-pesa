<?php require_once 'config.php';
require_once 'auth.php';
require_login();
$paid = has_paid();
$user = htmlspecialchars(current_user()); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/api/assets/style.css">
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="logo">L</div><strong>LINKSPHERE AGENCIES</strong>
            </div>
            <p class="side-title">LIVE TASKS</p>
            <a href="<?php echo $paid ? 'surveys.php' : '#'; ?>" class="side-link <?php echo !$paid ? 'locked-link' : ''; ?>"><span>📋</span> Surveys <b>700</b></a>
            <a href="#" class="side-link locked-link"><span>✍️</span> Blogging <b>200</b></a><a href="#" class="side-link locked-link"><span>🎬</span> Watch and earn <b>50</b></a>
            <a href="<?php echo $paid ? 'trivia.php' : '#'; ?>" class="side-link <?php echo !$paid ? 'locked-link' : ''; ?>"><span>💡</span> Trivia <b>300</b></a>
            <a href="<?php echo $paid ? 'chat.php' : '#'; ?>" class="side-link <?php echo !$paid ? 'locked-link' : ''; ?>"><span>💬</span> Global Chat <b>AI</b></a>
            <p class="side-title">ACCOUNT</p><a href="dashboard.php" class="side-link active"><span>✅</span> Activate Account</a><a href="#" class="side-link"><span>👤</span> Profile</a><a href="logout.php" class="side-link"><span>🚪</span> Sign Out</a>
        </aside>
        <main class="main">
            <header class="topbar"><button id="menuBtn" class="menu-btn">☰</button>
                <div></div>
                <div class="top-actions">
                    <div class="balance">Balance: <strong id="balanceText">KES 0</strong></div>
                    <div class="avatar"><?php echo strtoupper(substr($user, 0, 1)); ?></div>
                </div>
            </header>
            <section class="content">
                <div class="greeting">
                    <h1>Hello, <?php echo $user; ?> 👋</h1>
                    <p class="muted"><?php echo $paid ? 'Your account is verified. You can now access Surveys, Trivia and Global Chat.' : 'Please activate your account to unlock Surveys, Trivia and Global Chat.'; ?></p>
                </div>
                <?php if ($paid): ?><div class="success-notice">✅ Account verified successfully. Services are now unlocked.</div><?php else: ?><div class="notice">⚠️ Verify your account to access other services</div><?php endif; ?>
                <div class="amount-card"><span>● Activation Amount</span><strong>Ksh <?php echo ACTIVATION_AMOUNT; ?></strong></div>
                <?php if (!$paid): ?><div class="info-card">
                        <div class="icon">💳</div>
                        <div>
                            <h2>Amount Required: Ksh <?php echo ACTIVATION_AMOUNT; ?></h2>
                            <p>This is a one-time activation fee. Enter your M-Pesa number below to receive an STK push payment request.</p>
                        </div>
                    </div>
                    <form id="activationForm" class="payment-card"><label>M-Pesa Number</label>
                        <div class="phone-row"><span>+254</span><input type="text" name="msisdn" id="msisdn" placeholder="7XXXXXXXX" required></div>
                        <p class="locked">🔒 STK prompt will be sent to this number</p><button class="primary-btn" type="submit" id="payBtn">🔐 Verify Your Account Now</button>
                        <p id="payStatus" class="status"></p>
                    </form><?php else: ?><div class="payment-card">
                        <h2>Unlocked Services</h2>
                        <p>Choose Surveys, Trivia, or Global Chat from the sidebar menu.</p>
                    </div><?php endif; ?>
            </section>
        </main>
    </div>
    <script src="assets/app.js"></script>
    <script>
        document.querySelectorAll('.locked-link').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            alert('Please pay the activation fee first.');
        }));
        const form = document.getElementById('activationForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('payBtn');
                const status = document.getElementById('payStatus');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner"></span> Sending STK Push...';
                status.textContent = '';
                try {
                    const res = await fetch('megapay-initiate.php', {
                        method: 'POST',
                        body: new FormData(this)
                    });
                    const data = await res.json();
                    status.className = data.success ? 'status ok' : 'status error';
                    status.textContent = data.message || 'Payment request failed.';
                    if (data.success) {
                        setTimeout(() => {
                            location.href = 'payment-pending.php?reference=' + encodeURIComponent(data.reference || '');
                        }, 1500);
                    }
                } catch (err) {
                    status.className = 'status error';
                    status.textContent = 'Server error. Please try again.';
                }
                btn.disabled = false;
                btn.innerHTML = '🔐 Verify Your Account Now';
            });
        }
    </script>
</body>

</html>
