<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
    <div class="auth-page">
        <form class="auth-card" id="signupForm">
            <div class="auth-brand">
                <div class="logo">L</div><strong>LINKSPHERE AGENCIES</strong>
            </div>
            <h1>Create Account</h1>
            <p class="muted">Sign up to access your dashboard.</p>
            <div class="form-group"><label>Full Name</label><input class="input" id="fullName" name="full_name" required></div>
            <div class="form-group"><label>Email</label><input class="input" id="signupEmail" name="email" type="email" required></div>
            <div class="form-group"><label>Password</label><input class="input" id="signupPassword" name="password" type="password" required></div><button class="primary-btn">Sign Up</button>
            <p>Already have an account? <a href="signin.php" style="color:var(--primary);font-weight:900">Sign In</a></p>
        </form>
    </div>
    <script src="/assets/app.js"></script>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fullName = document.getElementById('fullName').value.trim();
            const emailAddress = document.getElementById('signupEmail').value.trim().toLowerCase();
            const userPassword = document.getElementById('signupPassword').value;

            if (!fullName || !emailAddress || !userPassword) {
                alert('Please fill all fields.');
                return;
            }

            const user = {
                name: fullName,
                email: emailAddress,
                password: userPassword
            };

            localStorage.setItem('ls_account_' + emailAddress, JSON.stringify(user));
            setCookie('ls_user', fullName, 30);
            setCookie('ls_email', emailAddress, 30);

            if (!getCookie('ls_paid')) {
                setCookie('ls_paid', '0', 30);
            }

            window.location.href = 'dashboard.php';
        });
    </script>
</body>

</html>
