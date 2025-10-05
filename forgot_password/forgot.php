
<?php
include_once '../connection.php';
session_start();

try {
  if (isset($_SESSION['user_id']) && $_SESSION['user_type']) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $query = $con->query($sql) or die($con->error);
    $row = $query->fetch_assoc();
    $account_type = $row['user_type'];
    if ($account_type == 'admin') {
      echo '<script>window.location.href="admin/dashboard.php";</script>';
    } elseif ($account_type == 'secretary') {
      echo '<script>window.location.href="secretary/dashboard.php";</script>';
    } else {
      echo '<script>window.location.href="resident/dashboard.php";</script>';
    }
  }

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
} catch (Exception $e) {
  echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - Forgot Password</title>

  <!-- Preload cover image -->
  <link rel="preload" href="../assets/logo/cover.JPG" as="image" fetchpriority="high">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
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
      transition: transform 0.3s ease;
    }

    .custom-navbar .navbar-brand:hover {
      transform: translateY(-2px);
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
      letter-spacing: -0.5px;
    }

    .navbar-toggler {
      border: 2px solid #b30000;
      padding: 6px 10px;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23b30000' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .nav-link {
      color: #333 !important;
      font-weight: 500;
      padding: 8px 16px !important;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin: 0 4px;
    }

    .nav-link i {
      margin-right: 8px;
    }

    .nav-link:hover {
      background: #b30000 !important;
      color: white !important;
      transform: translateY(-2px);
    }

    .nav-link.active {
      background: #b30000 !important;
      color: white !important;
    }

    .content-wrapper {
      background-color: rgba(0, 0, 0, 0.40);
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      background-attachment: fixed;
      min-height: calc(100vh - 120px);
      padding: 60px 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .forgot-card {
      max-width: 480px;
      width: 100%;
      margin: 0 auto;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      border: none;
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

    .forgot-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 32px 24px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .logo-container {
      position: relative;
      z-index: 1;
      margin-bottom: 16px;
    }

    .logo-main {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 5px solid white;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
      object-fit: cover;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    .forgot-title {
      color: white;
      font-size: 24px;
      font-weight: 700;
      position: relative;
      z-index: 1;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      margin: 0;
    }

    .forgot-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 14px;
      margin-top: 8px;
    }

    .forgot-body {
      background: white;
      padding: 36px 32px;
    }

    .form-group {
      margin-bottom: 24px;
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
      border-right: none;
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
      border-color: #b30000;
      box-shadow: none;
    }

    .btn-forgot {
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
      letter-spacing: 1px;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .btn-forgot:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
      background: linear-gradient(135deg, #8b0000 0%, #b30000 100%);
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
      margin-top: 10px;
    }

    .footer-custom {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      color: #333;
      text-align: center;
      padding: 20px;
      font-weight: 500;
      border-top: 3px solid #b30000;
      font-size: 14px;
    }

    .footer-custom .fas {
      color: #b30000;
      margin-right: 8px;
    }

    /* Tablet Styles (768px - 991px) */
    @media (max-width: 991px) {
      .custom-navbar .brand-text {
        font-size: 20px;
      }

      .custom-navbar .brand-image {
        width: 45px;
        height: 45px;
      }

      .navbar-nav {
        margin-top: 10px;
      }

      .navbar-collapse {
        padding: 0 10px;
      }

      .nav-link {
        margin: 4px 0;
        text-align: left;
      }

      .nav-link i {
        margin-right: 10px;
        width: 20px;
        display: inline-block;
        text-align: center;
      }

      .forgot-card {
        max-width: 90%;
      }

      .content-wrapper {
        padding: 40px 15px;
      }
    }

    /* Mobile Styles (481px - 767px) */
    @media (max-width: 767px) {
      .navbar-toggler {
        border: 2px solid #b30000;
        margin-right: 10px !important;
      }

      .navbar-brand {
        margin-left: 10px;
      }

      .custom-navbar {
        padding: 10px 0;
      }

      .custom-navbar .brand-text {
        font-size: 18px;
      }

      .custom-navbar .brand-image {
        width: 40px;
        height: 40px;
        border: 2px solid #b30000;
      }

      .content-wrapper {
        background-attachment: scroll;
        padding: 30px 15px;
        min-height: calc(100vh - 100px);
      }

      .forgot-card {
        max-width: 100%;
        margin: 0;
        border-radius: 20px;
      }

      .forgot-header {
        padding: 28px 20px;
      }

      .forgot-title {
        font-size: 22px;
        letter-spacing: 0.5px;
      }

      .forgot-subtitle {
        font-size: 13px;
      }

      .forgot-body {
        padding: 28px 24px;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .input-group-text {
        font-size: 16px;
        padding: 10px 14px;
      }

      .form-control {
        padding: 10px 14px;
        font-size: 14px;
      }

      .btn-forgot {
        font-size: 15px;
        padding: 12px 28px;
        letter-spacing: 0.5px;
      }

      .footer-custom {
        padding: 15px;
        font-size: 12px;
      }
    }

    /* Small Mobile Styles (max-width: 480px) */
    @media (max-width: 480px) {
      .custom-navbar .brand-text {
        font-size: 16px;
      }

      .custom-navbar .brand-image {
        width: 38px;
        height: 38px;
      }

      .content-wrapper {
        padding: 20px 10px;
      }

      .forgot-card {
        border-radius: 16px;
      }

      .forgot-header {
        padding: 24px 16px;
      }

      .forgot-title {
        font-size: 20px;
      }

      .forgot-subtitle {
        font-size: 12px;
      }

      .forgot-body {
        padding: 24px 20px;
      }

      .form-group {
        margin-bottom: 18px;
      }

      .input-group-text {
        font-size: 15px;
        padding: 10px 12px;
      }

      .form-control {
        padding: 10px 12px;
        font-size: 13px;
      }

      .form-control::placeholder {
        font-size: 13px;
      }

      .btn-forgot {
        font-size: 14px;
        padding: 12px 24px;
      }

      .text-center span {
        font-size: 13px !important;
      }

      .text-center a {
        font-size: 13px !important;
      }

      .footer-custom {
        padding: 12px 10px;
        font-size: 11px;
        line-height: 1.6;
      }
    }

    /* Extra Small Mobile (max-width: 360px) */
    @media (max-width: 360px) {
      .custom-navbar .brand-text {
        font-size: 14px;
      }

      .custom-navbar .brand-image {
        width: 35px;
        height: 35px;
      }

      .forgot-title {
        font-size: 18px;
      }

      .forgot-subtitle {
        font-size: 11px;
      }

      .forgot-body {
        padding: 20px 16px;
      }

      .input-group-text {
        padding: 10px;
      }

      .form-control {
        padding: 10px;
        font-size: 12px;
      }

      .btn-forgot {
        font-size: 13px;
        padding: 11px 20px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .content-wrapper {
        padding: 20px 15px;
        min-height: auto;
      }

      .forgot-header {
        padding: 20px 16px;
      }

      .forgot-title {
        font-size: 18px;
      }

      .forgot-subtitle {
        font-size: 12px;
      }

      .forgot-body {
        padding: 20px 24px;
      }

      .form-group {
        margin-bottom: 16px;
      }

      @keyframes float {
        0%,
        100% {
          transform: translateY(0);
        }
        50% {
          transform: translateY(-5px);
        }
      }
    }

    /* Ensure proper touch targets on mobile */
    @media (hover: none) and (pointer: coarse) {
      .nav-link,
      .btn-forgot {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text">Sending OTP...</div>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md custom-navbar">
      <div class="container">
        <a href="../index.php" class="navbar-brand">
          <img src="../assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="brand-image">
          <span class="brand-text">Barangay Hulo</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="../index.php" class="nav-link">
                <i class="fas fa-home"></i> Home
              </a>
            </li>
            <li class="nav-item">
              <a href="../register.php" class="nav-link">
                <i class="fas fa-user-plus"></i> Register
              </a>
            </li>
            <li class="nav-item">
              <a href="../login.php" class="nav-link">
                <i class="fas fa-sign-in-alt"></i> Login
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content">
        <div class="container">
          <div class="forgot-card">
            <div class="forgot-header">
              <div class="logo-container">
                <i class="fas fa-key" style="font-size: 80px; color: white;"></i>
              </div>
              <h1 class="forgot-title">Forgot Password</h1>
              <p class="forgot-subtitle">Enter your username to receive an OTP</p>
            </div>

            <div class="forgot-body">
              <form id="forgotForm" method="post">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username or Resident Number" required>
                  </div>
                </div>

                <button type="submit" class="btn btn-forgot">
                  <i class="fas fa-paper-plane"></i> Send OTP
                </button>

                <div class="text-center mt-3">
                  <span style="color: #666; font-size: 14px;">Remember your password? </span>
                  <a href="../login.php" style="color: #b30000; font-weight: 500; text-decoration: none;">Login here</a>
                </div>
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

  <!-- jQuery -->
  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/dist/js/adminlte.js"></script>
  <script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      $("#forgotForm").submit(function(e) {
        e.preventDefault();
        var username = $("#username").val();

        if (username == '') {
          Swal.fire({
            title: '<strong class="text-warning">Required</strong>',
            icon: 'warning',
            html: '<b>Please enter your username</b>',
            width: '400px',
            confirmButtonColor: '#b30000',
          });
          return;
        }

        // Show loading overlay
        $('#loadingOverlay').addClass('active');

        $.ajax({
          url: 'forgot_password_send_otp.php',
          type: 'POST',
          data: {
            username: username
          },
          dataType: 'json',
          success: function(data) {
            $('#loadingOverlay').removeClass('active');

            if (data.success) {
              Swal.fire({
                title: '<strong class="text-success">OTP Sent!</strong>',
                icon: 'success',
                html: '<b>An OTP has been sent to your email.<br>Redirecting to verification...</b>',
                width: '400px',
                confirmButtonColor: '#b30000',
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 2000
              }).then(() => {
                window.location.href = 'forgot_password_verify.php';
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
