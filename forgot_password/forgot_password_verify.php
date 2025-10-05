<?php
session_start();
include_once '../connection.php';

// Check if user has pending password reset
if (!isset($_SESSION['forgot_password_user_id']) || !isset($_SESSION['forgot_password_email'])) {
    header('Location: forgot.php');
    exit();
}

$email = $_SESSION['forgot_password_email'];

// Get barangay information
$sql = "SELECT * FROM `barangay_information`";
$query = $con->prepare($sql) or die($con->error);
$query->execute();
$result = $query->get_result();
while ($row = $result->fetch_assoc()) {
  $barangay = $row['barangay'];
  $zone = $row['zone'];
  $district = $row['district'];
  $image = $row['image'];
  $image_path = $row['image_path'];
  $id = $row['id'];
  $postal_address = $row['postal_address'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - Verify OTP</title>

  <link rel="preload" href="../assets/logo/cover.JPG" as="image" fetchpriority="high">
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: rgba(0, 0, 0, 0.40);
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      background-attachment: fixed;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .verify-container {
      max-width: 500px;
      width: 100%;
    }

    .verify-card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .verify-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 40px 30px;
      text-align: center;
      color: white;
    }

    .verify-header i {
      font-size: 60px;
      margin-bottom: 15px;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .verify-header h2 {
      font-size: 28px;
      font-weight: 700;
      margin: 0;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .verify-header p {
      margin: 10px 0 0 0;
      opacity: 0.9;
    }

    .verify-body {
      padding: 40px 30px;
    }

    .email-info {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 25px;
      text-align: center;
    }

    .email-info i {
      color: #b30000;
      margin-right: 8px;
    }

    .email-info strong {
      color: #b30000;
    }

    .otp-input-group {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 25px 0;
    }

    .otp-input {
      width: 50px;
      height: 60px;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      border: 2px solid #ddd;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .otp-input:focus {
      border-color: #b30000;
      outline: none;
      box-shadow: 0 0 0 3px rgba(179, 0, 0, 0.1);
    }

    .btn-verify {
      width: 100%;
      padding: 15px;
      font-size: 18px;
      font-weight: 600;
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 20px;
    }

    .btn-verify:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(179, 0, 0, 0.3);
    }

    .resend-section {
      text-align: center;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    .btn-resend {
      background: none;
      border: none;
      color: #b30000;
      font-weight: 600;
      cursor: pointer;
      text-decoration: underline;
    }

    .btn-resend:hover {
      color: #8b0000;
    }

    .timer {
      color: #666;
      font-size: 14px;
      margin-top: 10px;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #666;
    }

    .back-link a {
      color: #b30000;
      font-weight: 600;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    .loading-overlay.active {
      display: flex;
    }

    .loading-content {
      text-align: center;
      color: white;
    }

    .loading-spinner {
      width: 60px;
      height: 60px;
      border: 5px solid rgba(255, 255, 255, 0.3);
      border-top: 5px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 20px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-text {
      font-size: 18px;
      font-weight: 600;
      margin-top: 10px;
    }

    .loading-subtext {
      font-size: 14px;
      opacity: 0.8;
      margin-top: 5px;
    }
  </style>
</head>
<body>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-content">
    <div class="loading-spinner"></div>
    <div class="loading-text">Verifying Your Code...</div>
    <div class="loading-subtext">Please wait a moment</div>
  </div>
</div>

<div class="verify-container">
  <div class="verify-card">
    <div class="verify-header">
      <i class="fas fa-shield-alt"></i>
      <h2>Verify OTP</h2>
      <p>Enter the verification code to reset password</p>
    </div>

    <div class="verify-body">
      <div class="email-info">
        <i class="fas fa-info-circle"></i>
        We've sent a 6-digit code to:<br>
        <strong><?php echo htmlspecialchars($email); ?></strong>
      </div>

      <form id="otpForm" method="POST">
        <div class="otp-input-group">
          <input type="text" class="otp-input" maxlength="1" id="otp1" autocomplete="off">
          <input type="text" class="otp-input" maxlength="1" id="otp2" autocomplete="off">
          <input type="text" class="otp-input" maxlength="1" id="otp3" autocomplete="off">
          <input type="text" class="otp-input" maxlength="1" id="otp4" autocomplete="off">
          <input type="text" class="otp-input" maxlength="1" id="otp5" autocomplete="off">
          <input type="text" class="otp-input" maxlength="1" id="otp6" autocomplete="off">
        </div>

        <button type="submit" class="btn-verify">
          <i class="fas fa-check-circle"></i> Verify & Continue
        </button>
      </form>

      <div class="resend-section">
        <p class="timer" id="timer">Resend code in <span id="countdown">60</span>s</p>
        <button type="button" class="btn-resend" id="resendBtn" style="display: none;">
          <i class="fas fa-redo"></i> Resend Code
        </button>
      </div>

      <div class="back-link">
        <a href="forgot.php"><i class="fas fa-arrow-left"></i> Back to Forgot Password</a>
      </div>
    </div>
  </div>
</div>

<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
  $('#otp1').focus();

  // Auto-move to next input
  $('.otp-input').on('input', function() {
    const input = $(this);
    const val = input.val();
    
    // Only allow numbers
    if (!/^\d$/.test(val)) {
      input.val('');
      return;
    }
    
    // Move to next input
    const nextInput = input.next('.otp-input');
    if (val && nextInput.length) {
      nextInput.focus();
    }
  });

  // Handle backspace
  $('.otp-input').on('keydown', function(e) {
    if (e.key === 'Backspace' && !$(this).val()) {
      $(this).prev('.otp-input').focus();
    }
  });

  // Handle paste
  $('#otp1').on('paste', function(e) {
    e.preventDefault();
    const pastedData = e.originalEvent.clipboardData.getData('text');
    const digits = pastedData.replace(/\D/g, '').slice(0, 6);
    
    for (let i = 0; i < digits.length; i++) {
      $('#otp' + (i + 1)).val(digits[i]);
    }
    
    if (digits.length === 6) {
      $('#otp6').focus();
    }
  });

  // Form submission
  $('#otpForm').on('submit', function(e) {
    e.preventDefault();
    
    // Collect OTP
    const otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + 
                $('#otp4').val() + $('#otp5').val() + $('#otp6').val();
    
    if (otp.length !== 6) {
      Swal.fire({
        icon: 'warning',
        title: 'Incomplete Code',
        text: 'Please enter all 6 digits',
        confirmButtonColor: '#b30000'
      });
      return;
    }
    
    // Show loading overlay
    $('#loadingOverlay').addClass('active');
    
    // Verify OTP
    $.ajax({
      url: 'forgot_password_verify_otp.php',
      type: 'POST',
      data: { otp: otp },
      dataType: 'json',
      success: function(data) {
        // Hide loading overlay
        $('#loadingOverlay').removeClass('active');
        
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'OTP Verified!',
            text: 'Redirecting to reset password...',
            confirmButtonColor: '#b30000',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000
          }).then(() => {
            window.location.href = 'reset_password.php';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Verification Failed',
            text: data.message,
            confirmButtonColor: '#b30000'
          });
          
          // Clear inputs
          $('.otp-input').val('');
          $('#otp1').focus();
        }
      },
      error: function(xhr, status, error) {
        $('#loadingOverlay').removeClass('active');
        
        console.error('AJAX Error:', error);
        console.error('Response:', xhr.responseText);
        
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong. Please try again.',
          confirmButtonColor: '#b30000'
        });
      }
    });
  });

  // Countdown timer 1 min
  let countdown = 60;
  const timer = setInterval(function() {
    countdown--;
    $('#countdown').text(countdown);
    
    if (countdown <= 0) {
      clearInterval(timer);
      $('#timer').hide();
      $('#resendBtn').show();
    }
  }, 1000);

  // Resend OTP
  $('#resendBtn').on('click', function() {
    $.ajax({
      url: 'forgot_password_resend_otp.php',
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Code Resent',
            text: 'A new verification code has been sent to your email.',
            confirmButtonColor: '#b30000'
          });
          
          // Reset timer
          countdown = 60;
          $('#countdown').text(countdown);
          $('#timer').show();
          $('#resendBtn').hide();
          
          const newTimer = setInterval(function() {
            countdown--;
            $('#countdown').text(countdown);
            
            if (countdown <= 0) {
              clearInterval(newTimer);
              $('#timer').hide();
              $('#resendBtn').show();
            }
          }, 1000);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
            confirmButtonColor: '#b30000'
          });
        }
      }
    });
  });
});
</script>

</body>
</html>
