<?php
define('MEGAPAY_API_KEY', 'PASTE_YOUR_MEGAPAY_API_KEY_HERE');
define('MEGAPAY_EMAIL', 'you@example.com');
define('MEGAPAY_ENDPOINT', 'https://megapay.co.ke/backend/v1/initiatestk');
define('ACTIVATION_AMOUNT', 100);

// IMPORTANT: replace yourdomain.com with your real domain after upload.
// Ask MegaPay to send payment callbacks to this URL if they support callback URLs.
define('CALLBACK_URL', 'https://yourdomain.com/megapay-callback.php');
?>
