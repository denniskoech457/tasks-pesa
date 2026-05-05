<?php
define('MEGAPAY_API_KEY', 'MGPYlWU6lMpS');
define('MEGAPAY_EMAIL', 'denniskoskey5@gmail.com');
define('MEGAPAY_ENDPOINT', 'https://megapay.co.ke/backend/v1/initiatestk');
define('ACTIVATION_AMOUNT', 100);

// IMPORTANT: replace yourdomain.com with your real domain after upload.
// Ask MegaPay to send payment callbacks to this URL if they support callback URLs.
define('CALLBACK_URL', 'https://tasks-pesa.vercel.app/api/megapay-callback.php');
?>
