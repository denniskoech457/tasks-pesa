<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="/assets/style.css">
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
            <p>No account? <a href="signup.php" style="color:var(--primary);font-weight:900">Create Account</a></p>
        </form>
    </div>
    <script src="/assets/app.js"></script>
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

            window.location.href = 'dashboard.php';
        });
    </script>
</body>

</html>
