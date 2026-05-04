<?php require_once 'auth.php';
require_paid(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveys</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
    <div class="page">
        <header class="simple-top"><a href="dashboard.php">← Dashboard</a>
            <div class="balance">Balance: <strong id="balanceText">KES 0</strong></div>
        </header>
        <section class="tasks">
            <h1>Survey Tasks</h1>
            <p>Complete each survey and earn KES 700 reward.</p>
            <div class="task-grid">
                <form class="task-card reward-form" data-reward="700">
                    <h2>Survey 1: Customer Experience</h2><label>1. Do you use mobile money daily?<input required></label><label>2. Which service do you use most?<input required></label><label>3. Rate internet reliability.<input required></label><label>4. What feature matters most?<input required></label><label>5. Any improvement suggestion?<input required></label><button class="primary-btn">Submit & Earn KES 700</button>
                </form>
                <form class="task-card reward-form" data-reward="700">
                    <h2>Survey 2: Online Services</h2><label>1. Do you shop online?<input required></label><label>2. Preferred payment method?<input required></label><label>3. How often do you use apps?<input required></label><label>4. What makes a website trustworthy?<input required></label><label>5. Your final feedback?<input required></label><button class="primary-btn">Submit & Earn KES 700</button>
                </form>
            </div>
        </section>
    </div>
    <script src="/assets/app.js"></script>
</body>

</html>
