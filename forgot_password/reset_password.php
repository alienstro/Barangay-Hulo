<?php
session_start();
include_once '../connection.php';

// Check if OTP was verified
if (!isset($_SESSION['forgot_password_user_id']) || !isset($_SESSION['otp_verified_for_reset'])) {
    header('Location: forgot.php');
    exit();
}

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
  <title>Barangay Hulo - Reset Password</title>
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }

    .custom-navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
      padding: 12px 0;
      border-bottom: 3px solid #b30000;
    }

    .custom-navbar .navbar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .custom-navbar .brand-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #b30000;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .custom-navbar .brand-text {
      color: #b30000;
      font-weight: 700;
      font-size: 24px;
      margin: 0;
    }

    .content-wrapper {
      background-color: rgba(0, 0, 0, 0.40);
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      min-height: calc(100vh - 120px);
      padding: 60px 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .reset-card {
      max-width: 480px;
      margin: 0 auto;
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

    .reset-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 32px 24px;
      text-align: center;
      color: white;
    }

    .reset-header i {
      font-size: 60px;
      margin-bottom: 15px;
    }

    .reset-header h1 {
      font-size: 24px;
      font-weight: 700;
      text-transform: uppercase;
      margin: 0;
    }

    .reset-header p {
      margin: 10px 0 0 0;
      opacity: 0.9;
      font-size: 14px;
    }

    .reset-body {
      background: white;
      padding: 36px 32px;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      font-weight: 500;
      color: #333;
      margin-bottom: 8px;
      display: block;
    }

    .input-group {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      box-shadow: 0 4px 16px rgba(179, 0, 0, 0.2);
      transform: translateY(-2px);
    }

    .input-group-text {
      background: white;
      border: none;
      color: #b30000;
      font-size: 18px;
      padding: 12px 16px;
    }

    .form-control {
      border: none;
      padding: 12px 16px;
      font-size: 14px;
      height: auto;
      font-family: 'Poppins', sans-serif;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .btn-reset {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      padding: 14px 32px;
      width: 100%;
      transition: all 0.3s ease;
      text-transform: uppercase;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .btn-reset:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
    }

    .password-requirements {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 13px;
    }

    .password-requirements ul {
      margin: 10px 0 0 20px;
      padding: 0;
    }

    .password-requirements li {
      color: #666;
      margin: 5px 0;
    }

    .toggle-password {
      cursor: pointer;
      color: #b30000;
    }

    .toggle-password:hover {
      color: #8b0000;
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
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .loading-text {
      font-size: 18px;
      font-weight: 600;
    }

    .footer-custom {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      color: #333;
      text-align: center;
      padding: 20px 0;
      font-weight: 500;
      border-top: 3px solid #b30000;
    }

    .footer-custom .fas {
      color: #b30000;
      margin-right: 8px;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text">Resetting Password...</div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md custom-navbar">
      <div class="container">
        <a href="../index.php" class="navbar-brand">
          <img src="../assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="brand-image">
          <span class="brand-text">Barangay Hulo</span>
        </a>
      </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content">
        <div class="container">
          <div class="reset-card">
            <div class="reset-header">
              <i class="fas fa-lock"></i>
              <h1>Reset Password</h1>
              <p>Enter your new password</p>
            </div>

            <div class="reset-body">
              <div class="password-requirements">
                <strong><i class="fas fa-info-circle"></i> Password Requirements:</strong>
                <ul>
                  <li>At least 8 characters long</li>
                  <li>Passwords must match</li>
                </ul>
              </div>

              <form id="resetPasswordForm" method="post">
                <div class="form-group">
                  <label>New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    <div class="input-group-append">
                      <span class="input-group-text toggle-password" id="toggle_new_password">
                        <i class="fas fa-eye-slash"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Confirm Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                    <div class="input-group-append">
                      <span class="input-group-text toggle-password" id="toggle_confirm_password">
                        <i class="fas fa-eye-slash"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-reset">
                  <i class="fas fa-check-circle"></i> Reset Password
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="main-footer footer-custom">
      <i class="fas fa-map-marker-alt"></i> 91 Coronado, Barangay Hulo, Mandaluyong, Philippines
    </footer>
  </div>

  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/plugins/adminlte.js"></script>
  <script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      // Toggle password visibility
      $("#toggle_new_password").on('click', function() {
        let input = $('#new_password');
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          $(this).find('i').removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
          input.attr("type", "password");
          $(this).find('i').removeClass("fa-eye").addClass("fa-eye-slash");
        }
      });

      $("#toggle_confirm_password").on('click', function() {
        let input = $('#confirm_password');
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          $(this).find('i').removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
          input.attr("type", "password");
          $(this).find('i').removeClass("fa-eye").addClass("fa-eye-slash");
        }
      });

      // Form submission
      $("#resetPasswordForm").submit(function(e) {
        e.preventDefault();

        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();

        // Validation
        if (new_password.length < 8) {
          Swal.fire({
            title: '<strong class="text-warning">Invalid Password</strong>',
            icon: 'warning',
            html: '<b>Password must be at least 8 characters long</b>',
            width: '400px',
            confirmButtonColor: '#b30000',
          });
          return;
        }

        if (new_password !== confirm_password) {
          Swal.fire({
            title: '<strong class="text-danger">Password Mismatch</strong>',
            icon: 'error',
            html: '<b>Passwords do not match</b>',
            width: '400px',
            confirmButtonColor: '#b30000',
          });
          return;
        }

        // Show loading overlay
        $('#loadingOverlay').addClass('active');

        $.ajax({
          url: 'reset_password_process.php',
          type: 'POST',
          data: {
            new_password: new_password,
            confirm_password: confirm_password
          },
          dataType: 'json',
          success: function(data) {
            $('#loadingOverlay').removeClass('active');

            if (data.success) {
              Swal.fire({
                title: '<strong class="text-success">Success!</strong>',
                icon: 'success',
                html: '<b>Password reset successfully!<br>Redirecting to login...</b>',
                width: '400px',
                confirmButtonColor: '#b30000',
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 2000
              }).then(() => {
                window.location.href = '../login.php';
              });
            } else {
              Swal.fire({
                title: '<strong class="text-danger">Error</strong>',
                icon: 'error',
                html: '<b>' + data.message + '</b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              });
            }
          },
          error: function(xhr, status, error) {
            $('#loadingOverlay').removeClass('active');
            console.error('Error:', error);

            Swal.fire({
              title: '<strong class="text-danger">Error</strong>',
              icon: 'error',
              html: '<b>Something went wrong. Please try again.</b>',
              width: '400px',
              confirmButtonColor: '#b30000',
            });
          }
        });
      });
    });
  </script>
</body>

</html>
