<?php
// MegaPay credentials
// Replace these with your real MegaPay API credentials before deployment.
define('MEGAPAY_API_KEY', 'MGPYlWU6lMpS');
define('MEGAPAY_EMAIL', 'denniskoskey5@gmail.com');

// MegaPay endpoints
define('MEGAPAY_INITIATE_ENDPOINT', 'https://megapay.co.ke/backend/v1/initiatestk');
define('MEGAPAY_STATUS_ENDPOINT', 'https://megapay.co.ke/backend/v1/transactionstatus');

// Backward compatibility for older files
define('MEGAPAY_ENDPOINT', MEGAPAY_INITIATE_ENDPOINT);

define('ACTIVATION_AMOUNT', 100);

// If MegaPay asks for a callback URL, use your Vercel URL with /api.
// Example: https://your-project.vercel.app/api/megapay-callback.php
define('CALLBACK_URL', 'https://tasks-pesa.vercel.app/api/megapay-callback.php');
?>
