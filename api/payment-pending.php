<script>
const params = new URLSearchParams(window.location.search);
const transactionId = params.get("transaction_request_id") || "";

async function checkPayment() {
  try {
    const res = await fetch("/check-payment.php?transaction_request_id=" + encodeURIComponent(transactionId));
    const data = await res.json();

    document.getElementById("statusText").textContent = data.message || "Checking payment...";

    if (data.paid === true) {
      document.getElementById("statusText").textContent = "Payment verified. Redirecting...";
      setTimeout(() => {
        window.location.href = "/dashboard.php";
      }, 1500);
    }
  } catch (e) {
    document.getElementById("statusText").textContent = "Still checking payment...";
  }
}

setInterval(checkPayment, 5000);
checkPayment();
</script>
