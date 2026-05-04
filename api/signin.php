<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <style>
    :root {
      --primary: #2563eb;
      --secondary: #14b8a6;
      --accent: #f59e0b;
      --bg: #f6f9ff;
      --panel: #fff;
      --text: #172033;
      --muted: #64748b;
      --border: #dbeafe;
      --danger: #ef4444
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #eef6ff, #f7fffb);
      color: var(--text)
    }

    a {
      text-decoration: none;
      color: inherit
    }

    .auth-page {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px
    }

    .auth-card {
      width: 100%;
      max-width: 460px;
      background: #fff;
      border-radius: 22px;
      padding: 30px;
      box-shadow: 0 20px 60px rgba(37, 99, 235, .15)
    }

    .auth-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px
    }

    .logo {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      font-weight: 900;
      box-shadow: 0 8px 25px rgba(37, 99, 235, .25)
    }

    .auth-card h1 {
      margin: 0 0 8px
    }

    .muted {
      color: var(--muted)
    }

    .form-group {
      margin: 14px 0
    }

    .form-group label {
      font-weight: 800;
      display: block;
      margin-bottom: 7px
    }

    .input {
      width: 100%;
      padding: 15px;
      border: 1px solid var(--border);
      border-radius: 14px;
      font-size: 16px;
      outline: none
    }

    .input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, .09)
    }

    .primary-btn {
      width: 100%;
      border: 0;
      border-radius: 16px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: #fff;
      padding: 18px;
      font-weight: 900;
      font-size: 17px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px
    }

    .primary-btn:disabled {
      opacity: .7;
      cursor: not-allowed
    }

    .secondary-btn {
      border: 1px solid var(--border);
      background: #fff;
      color: var(--primary);
      border-radius: 12px;
      padding: 12px 14px;
      font-weight: 900;
      display: inline-flex
    }

    .app {
      display: flex;
      min-height: 100vh
    }

    .sidebar {
      width: 310px;
      background: #fff;
      border-right: 1px solid var(--border);
      padding: 24px 18px;
      position: fixed;
      top: 0;
      bottom: 0;
      overflow: auto
    }

    .brand {
      display: flex;
      gap: 12px;
      align-items: center;
      font-size: 20px;
      color: var(--primary)
    }

    .side-title {
      letter-spacing: 6px;
      color: #94a3b8;
      font-weight: 800;
      font-size: 12px;
      margin: 28px 0 12px
    }

    .side-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      border-radius: 14px;
      color: #24304f;
      text-decoration: none;
      font-weight: 800;
      margin-bottom: 8px
    }

    .side-link:hover,
    .side-link.active {
      background: #eef6ff
    }

    .side-link.locked-link {
      opacity: .55;
      cursor: not-allowed
    }

    .side-link span {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      border-radius: 12px;
      display: grid;
      place-items: center
    }

    .side-link b {
      margin-left: auto;
      background: #ccfbf1;
      color: #115e59;
      border-radius: 8px;
      padding: 4px 8px
    }

    .main {
      margin-left: 310px;
      width: calc(100% - 310px)
    }

    .topbar,
    .simple-top {
      height: 78px;
      background: #fff;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 28px;
      position: sticky;
      top: 0;
      z-index: 5
    }

    .menu-btn {
      display: none
    }

    .top-actions {
      display: flex;
      align-items: center;
      gap: 15px
    }

    .balance {
      background: #eff6ff;
      padding: 10px 15px;
      border-radius: 14px;
      font-weight: 800
    }

    .avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: #fff;
      font-weight: 900
    }

    .content {
      max-width: 860px;
      padding: 22px
    }

    .greeting {
      background: #fff;
      border-radius: 18px;
      padding: 20px;
      margin-bottom: 16px;
      box-shadow: 0 12px 35px rgba(37, 99, 235, .08)
    }

    .notice {
      border: 1px solid #fed7aa;
      background: #fff7ed;
      color: #9a3412;
      padding: 16px;
      border-radius: 14px;
      font-weight: 800;
      margin-bottom: 16px
    }

    .success-notice {
      border: 1px solid #bbf7d0;
      background: #f0fdf4;
      color: #166534;
      padding: 16px;
      border-radius: 14px;
      font-weight: 800;
      margin-bottom: 16px
    }

    .amount-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 16px;
      padding: 22px;
      font-size: 18px;
      margin-bottom: 16px
    }

    .amount-card strong {
      font-size: 28px
    }

    .info-card,
    .payment-card,
    .task-card {
      background: var(--panel);
      border-radius: 20px;
      padding: 24px;
      box-shadow: 0 12px 35px rgba(37, 99, 235, .09);
      margin-bottom: 18px
    }

    .info-card {
      display: flex;
      gap: 18px
    }

    .icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: var(--primary);
      display: grid;
      place-items: center;
      color: #fff
    }

    .phone-row {
      display: flex;
      border: 2px solid #dbeafe;
      border-radius: 14px;
      overflow: hidden;
      margin-top: 14px
    }

    .phone-row span {
      padding: 16px;
      background: #f1f5f9;
      font-weight: 900
    }

    .phone-row input {
      flex: 1;
      border: 0;
      padding: 16px;
      font-weight: 900;
      font-size: 18px;
      outline: none
    }

    .locked,
    .status {
      color: #94a3b8;
      font-weight: 700
    }

    .spinner {
      width: 18px;
      height: 18px;
      border: 3px solid #ffffff99;
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .8s linear infinite
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    .ok {
      color: #15803d
    }

    .error {
      color: #dc2626
    }

    .page {
      min-height: 100vh
    }

    .simple-top a {
      text-decoration: none;
      color: var(--primary);
      font-weight: 900
    }

    .tasks {
      max-width: 1100px;
      margin: auto;
      padding: 30px 18px
    }

    .task-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 18px
    }

    .task-card label {
      display: block;
      margin: 12px 0;
      font-weight: 700
    }

    .task-card input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 10px;
      margin-top: 6px
    }

    .chat-layout {
      display: grid;
      grid-template-columns: 280px 1fr;
      min-height: calc(100vh - 78px)
    }

    .chat-list {
      background: #fff;
      border-right: 1px solid var(--border);
      padding: 15px;
      overflow: auto
    }

    .chat-list button {
      display: flex;
      flex-direction: column;
      width: 100%;
      text-align: left;
      border: 0;
      background: #f8fafc;
      border-radius: 14px;
      padding: 14px;
      margin-bottom: 8px;
      cursor: pointer
    }

    .chat-list span {
      color: var(--muted)
    }

    .chat-box {
      display: flex;
      flex-direction: column;
      background: #f8fffe
    }

    .chat-head {
      background: #fff;
      padding: 18px;
      border-bottom: 1px solid var(--border)
    }

    .messages {
      flex: 1;
      padding: 18px;
      overflow: auto
    }

    .msg {
      max-width: 70%;
      padding: 12px 14px;
      border-radius: 14px;
      margin: 8px 0
    }

    .msg.user {
      background: var(--primary);
      color: white;
      margin-left: auto
    }

    .msg.bot {
      background: #fff;
      border: 1px solid var(--border)
    }

    .chat-form {
      display: flex;
      padding: 15px;
      background: #fff;
      border-top: 1px solid var(--border)
    }

    .chat-form input {
      flex: 1;
      padding: 14px;
      border: 1px solid #ddd;
      border-radius: 12px
    }

    .chat-form button {
      margin-left: 10px;
      border: 0;
      background: var(--primary);
      color: #fff;
      border-radius: 12px;
      padding: 0 22px;
      font-weight: 800
    }

    @media(max-width:850px) {
      .sidebar {
        transform: translateX(-100%);
        transition: .25s;
        z-index: 20
      }

      .sidebar.show {
        transform: translateX(0)
      }

      .main {
        margin-left: 0;
        width: 100%
      }

      .menu-btn {
        display: block;
        border: 0;
        background: var(--primary);
        color: #fff;
        border-radius: 10px;
        padding: 10px
      }

      .task-grid,
      .chat-layout {
        grid-template-columns: 1fr
      }

      .chat-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px
      }

      .topbar,
      .simple-top {
        padding: 0 14px
      }

      .content {
        padding: 16px
      }

      .auth-card {
        padding: 22px
      }

      .msg {
        max-width: 88%
      }
    }

    .big-spinner {
      width: 46px;
      height: 46px;
      border-width: 5px;
      display: inline-block;
    }
  </style>
</head>

<body>
  <div class="auth-page">
    <form class="auth-card" id="signinForm">
      <div class="auth-brand">
        <div class="logo">L</div><strong>LINKSPHERE AGENCIES</strong>
      </div>
      <h1>Welcome Back</h1>
      <p class="muted">Login to continue.</p>
      <div class="form-group"><label>Email</label><input class="input" id="signinEmail" name="email" type="email" required></div>
      <div class="form-group"><label>Password</label><input class="input" id="signinPassword" name="password" type="password" required></div><button class="primary-btn">Sign In</button>
      <p id="error" class="error"></p>
      <p>No account? <a href="/signup.php" style="color:var(--primary);font-weight:900">Create Account</a></p>
    </form>
  </div>
  <script>
    function getCookie(name) {
      const v = ('; ' + document.cookie).split('; ' + name + '=');
      if (v.length === 2) return decodeURIComponent(v.pop().split(';').shift());
      return ''
    }

    function setCookie(name, value, days = 30) {
      const d = new Date();
      d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
      document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax'
    }

    function deleteCookie(name) {
      document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/'
    }

    function getBalance() {
      return parseInt(localStorage.getItem('balance') || '0', 10)
    }

    function setBalance(v) {
      localStorage.setItem('balance', String(v));
      updateBalance()
    }

    function updateBalance() {
      document.querySelectorAll('#balanceText').forEach(el => el.textContent = 'KES ' + getBalance().toLocaleString())
    }
    updateBalance();
    const menuBtn = document.getElementById('menuBtn');
    if (menuBtn) {
      menuBtn.addEventListener('click', () => document.querySelector('.sidebar').classList.toggle('show'))
    }
    document.querySelectorAll('.reward-form').forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        if (form.dataset.done === '1') return alert('You already completed this task.');
        const reward = parseInt(form.dataset.reward || '0', 10);
        setBalance(getBalance() + reward);
        form.dataset.done = '1';
        const btn = form.querySelector('button');
        btn.textContent = 'Completed ✓ Reward Added';
        btn.disabled = true;
        alert('Task completed. KES ' + reward + ' added to your balance.');
      })
    });
  </script>
  <script>
    document.getElementById('signinForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const emailAddress = document.getElementById('signinEmail').value.trim().toLowerCase();
      const userPassword = document.getElementById('signinPassword').value;
      const errorBox = document.getElementById('error');

      const account = JSON.parse(localStorage.getItem('ls_account_' + emailAddress) || 'null');

      if (!account || account.password !== userPassword) {
        errorBox.textContent = 'Invalid email or password.';
        return;
      }

      setCookie('ls_user', account.name, 30);
      setCookie('ls_email', account.email, 30);

      if (!getCookie('ls_paid')) {
        setCookie('ls_paid', '0', 30);
      }

      window.location.href = '/dashboard.php';
    });
  </script>
</body>

</html>