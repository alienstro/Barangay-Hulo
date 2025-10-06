<?php
include_once 'connection.php';
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
  <title>Barangay Hulo - Login</title>

  <!-- Preload cover image -->
  <link rel="preload" href="assets/logo/cover.JPG" as="image" fetchpriority="high">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2/css/sweetalert2.min.css">
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
      position: fixed;
      width: 100%;
      z-index: 1000;
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

    .footer-link {
      color: #b30000;
      text-decoration: none;
      font-weight: 600;
      font-size: 17px;
      transition: all 0.3s ease;
      display: inline-block;
      padding: 8px 18px;
      border: 2px solid #b30000;
      border-radius: 8px;
      background: white;
    }

    .footer-link:hover {
      color: white;
      background: #b30000;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }


    .nav-link.active {
      background: #b30000 !important;
      color: white !important;
    }

    .content-wrapper {
      background-color: rgba(0, 0, 0, 0.40);
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      background-attachment: fixed;
      min-height: calc(100vh - 120px);
      padding: 120px 20px 60px 20px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
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

    .login-header {
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
      width: 140px;
      height: 140px;
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

    .login-title {
      color: white;
      font-size: 28px;
      font-weight: 700;
      position: relative;
      z-index: 1;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      margin: 0;
    }

    .login-body {
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

    .form-control::placeholder {
      color: #999;
      font-weight: 400;
    }

    .btn-login {
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

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
      background: linear-gradient(135deg, #8b0000 0%, #b30000 100%);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .forgot-link {
      color: #b30000;
      font-weight: 500;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .forgot-link:hover {
      color: #8b0000;
      text-decoration: underline;
    }

    .footer-custom {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      color: #333;
      padding: 35px 30px;
      font-weight: 500;
      border-top: 4px solid #b30000;
      font-size: 16px;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
    }

    .footer-custom .fas,
    .footer-custom .fab {
      color: #b30000;
      margin-right: 10px;
      font-size: 20px;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-section {
      margin-bottom: 0;
    }

    .footer-section h5 {
      color: #b30000;
      font-weight: 700;
      font-size: 20px;
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .footer-section p {
      margin: 8px 0;
      line-height: 1.6;
      font-size: 15px;
      color: #444;
    }

    .footer-section p strong {
      font-weight: 600;
      color: #222;
    }

    .google-maps-container {
      margin-top: 15px;
      text-align: left;
    }

    .footer-link {
      color: #b30000;
      text-decoration: none;
      font-weight: 700;
      font-size: 16px;
      transition: all 0.3s ease;
      display: inline-block;
      width: 100%;
      max-width: 350px;
      border: 3px solid #b30000;
      border-radius: 12px;
      background: white;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(179, 0, 0, 0.2);
    }

    .footer-link:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(179, 0, 0, 0.35);
      border-color: #8b0000;
    }

    .map-thumbnail {
      width: 100%;
      height: 160px;
      object-fit: cover;
      display: block;
      border-bottom: 3px solid #b30000;
    }

    .map-link-text {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 20px;
      background: white;
      color: #b30000;
      font-weight: 700;
      font-size: 16px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .footer-link:hover .map-link-text {
      background: #b30000;
      color: white;
    }

    .hotline-grid {
      display: block;
      background: rgba(179, 0, 0, 0.05);
      border-left: 4px solid #b30000;
      border-radius: 8px;
      padding: 12px 16px;
      margin: 12px 0;
      line-height: 1.6;
    }

    .hotline-grid p {
      margin: 4px 0;
      background: transparent;
      border: none;
      padding: 0;
      font-size: 16px;
    }

    .office-hours {
      margin-top: 12px;
    }

    .office-hours p {
      background: rgba(179, 0, 0, 0.05);
      padding: 10px 15px;
      border-radius: 6px;
      margin: 8px 0;
      border-left: 3px solid #b30000;
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

      .login-card {
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

      .login-card {
        max-width: 100%;
        margin: 0;
        border-radius: 20px;
      }

      .login-header {
        padding: 28px 20px;
      }

      .logo-main {
        width: 120px;
        height: 120px;
        border: 4px solid white;
      }

      .login-title {
        font-size: 24px;
        letter-spacing: 0.5px;
      }

      .login-body {
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

      .btn-login {
        font-size: 15px;
        padding: 12px 28px;
        letter-spacing: 0.5px;
      }

      .footer-custom {
        padding: 30px 20px;
        font-size: 14px;
      }

      .footer-section {
        margin-bottom: 25px;
      }

      .footer-section h5 {
        font-size: 18px;
        margin-bottom: 12px;
      }

      .footer-section p {
        font-size: 14px;
      }

      .footer-custom .fas,
      .footer-custom .fab {
        font-size: 18px;
      }

      .hotline-grid {
        grid-template-columns: 1fr;
        gap: 8px;
      }

      .footer-link {
        font-size: 15px;
        max-width: 100%;
      }

      .map-thumbnail {
        height: 140px;
      }

      .map-link-text {
        font-size: 14px;
        padding: 10px 16px;
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

      .login-card {
        border-radius: 16px;
      }

      .login-header {
        padding: 24px 16px;
      }

      .logo-main {
        width: 100px;
        height: 100px;
        border: 3px solid white;
      }

      .login-title {
        font-size: 20px;
      }

      .login-body {
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

      .btn-login {
        font-size: 14px;
        padding: 12px 24px;
      }

      .forgot-link {
        font-size: 13px;
      }

      .text-center span {
        font-size: 13px !important;
      }

      .footer-custom {
        padding: 25px 15px;
        font-size: 13px;
        line-height: 1.6;
      }

      .footer-section {
        margin-bottom: 20px;
      }

      .footer-section h5 {
        font-size: 16px;
        margin-bottom: 10px;
      }

      .footer-section p {
        font-size: 13px;
        margin: 6px 0;
      }

      .footer-custom .fas,
      .footer-custom .fab {
        font-size: 16px;
      }

      .hotline-grid {
        grid-template-columns: 1fr;
        gap: 8px;
      }

      .hotline-grid p {
        padding: 6px 10px;
        font-size: 13px;
      }

      .office-hours p {
        padding: 8px 12px;
        font-size: 13px;
      }

      .footer-link {
        font-size: 14px;
        max-width: 100%;
      }

      .map-thumbnail {
        height: 120px;
      }

      .map-link-text {
        font-size: 13px;
        padding: 10px 14px;
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

      .logo-main {
        width: 90px;
        height: 90px;
      }

      .login-title {
        font-size: 18px;
      }

      .login-body {
        padding: 20px 16px;
      }

      .input-group-text {
        padding: 10px;
      }

      .form-control {
        padding: 10px;
        font-size: 12px;
      }

      .btn-login {
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

      .login-header {
        padding: 20px 16px;
      }

      .logo-main {
        width: 80px;
        height: 80px;
      }

      .login-title {
        font-size: 18px;
      }

      .login-body {
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
      .btn-login,
      .forgot-link,
      #show_hide_password a {
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

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md custom-navbar">
      <div class="container">
        <a href="index.php" class="navbar-brand">
          <img src="assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="brand-image">
          <span class="brand-text">Barangay Hulo</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="fas fa-home"></i> Home
              </a>
            </li>
            <li class="nav-item">
              <a href="register.php" class="nav-link">
                <i class="fas fa-user-plus"></i> Register
              </a>
            </li>
            <li class="nav-item">
              <a href="login.php" class="nav-link active">
                <i class="fas fa-sign-in-alt"></i> Login
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="background-color:rgba(0,0,0,0.40); background-image: url('assets/logo/cover.JPG'); background-size:cover; background-position:center; background-repeat:no-repeat; background-blend-mode:overlay;">
      <div class="content">
        <div class="container">
          <div class="login-card">
            <div class="login-header">
              <div class="logo-container">
                <img src="assets/logo/LogoHulo.JPG" alt="Barangay Logo" class="logo-main">
              </div>
              <h1 class="login-title">Barangay Portal</h1>
            </div>

            <div class="login-body">
              <form id="loginForm" method="post">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username or Resident Number" required>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group" id="show_hide_password">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                      <span class="input-group-text" style="cursor: pointer; border-left: none;">
                        <a href="#" style="text-decoration:none; color: #b30000;"><i class="fas fa-eye-slash" aria-hidden="true"></i></a>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="text-right mb-3">
                  <a href="forgot_password/forgot.php" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-login">Sign In</button>

                <div class="text-center mt-3">
                  <span style="color: #666; font-size: 14px;">Don't have an account? </span>
                  <a href="register.php" class="forgot-link">Register here</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="main-footer footer-custom">
      <div class="footer-content">
        <div class="row">
          <div class="col-md-4 footer-section">
            <h5><i class="fas fa-phone-alt"></i> Emergency Hotlines</h5>
            <div class="hotline-grid">
              <p><strong>ONE HULO:</strong> 77982024</p>
              <p><strong>PNP:</strong> 0998-5987882</p>
              <p><strong>PRC:</strong> 143</p>
              <p><strong>DOH:</strong> (632) 8651-7800</p>
              <p><strong>MERALCO:</strong> 16211</p>
              <p><strong>BFP:</strong> (02) 8426-0246(02) / 8426-0219</p>
            </div>
          </div>

          <div class="col-md-4 footer-section">
            <h5><i class="fas fa-clock"></i> Office Hours</h5>
            <div class="office-hours">
              <p><strong>Monday to Friday:</strong><br>7:00 AM - 4:00 PM</p>
              <p><strong>Saturday:</strong><br>7:00 AM - 12:00 NN</p>
              <p><strong>Sunday & Holidays:</strong><br>CLOSED</p>
            </div>
          </div>

          <div class="col-md-4 footer-section">
            <h5><i class="fas fa-map-marker-alt"></i> Location</h5>
            <p>91 Coronado, Barangay Hulo<br>Mandaluyong, Philippines</p>
            <div class="google-maps-container">
              <a href="https://www.google.com/maps/place/Hulo+Barangay+Hall/@14.5729668,121.0284491,17.5z/data=!4m10!1m2!2m1!1sbarangay+hulo!3m6!1s0x3397c9ad79b7b58d:0xb2c89599c906a93d!8m2!3d14.5700982!4d121.0318749!15sCg1iYXJhbmdheSBodWxvkgERZ292ZXJubWVudF9vZmZpY2WqAUQQASoMIghiYXJhbmdheSggMh8QASIbMmJxRmB9YiOoG5evNO2MKTmRkZ25wJZHGr_ZMhEQAiINYmFyYW5nYXkgaHVsb-ABAA!16s%2Fg%2F11bzx3jt0t?hl=en&entry=ttu&g_ep=EgoyMDI1MTAwMS4wIKXMDSoASAFQAw%3D%3D"
                target="_blank"
                class="footer-link">
                <img src="./assets/logo/google_maps.png" alt="Barangay Hulo Map" class="map-thumbnail">
                <div class="map-link-text">
                  <span>View on Google Maps</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/dist/js/adminlte.js"></script>
  <script src="assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {

      $("#loginForm").submit(function(e) {
        e.preventDefault();
        var username = $("#username").val();
        var password = $("#password").val();
        if (username == '' || password == '') {
          Swal.fire({
            title: '<strong class="text-danger">WARNING</strong>',
            icon: 'warning',
            html: '<b>Username and Password is Required<b>',
            width: '400px',
          })
        } else {
          $.ajax({
            url: 'loginForm.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
              if (data == 'errorUsername' || data == 'errorPassword') {
                Swal.fire({
                  title: '<strong class="text-danger">ERROR</strong>',
                  icon: 'error',
                  html: '<b>Incorrect Username or Password<b>',
                  width: '400px',
                })
              } else if (data == 'unverified') {
                Swal.fire({
                  title: '<strong class="text-warning">Email Not Verified</strong>',
                  icon: 'warning',
                  html: '<b>Please verify your email address first.<br>Check your email for the OTP code.</b>',
                  width: '400px',
                  confirmButtonColor: '#b30000',
                  confirmButtonText: 'Verify Now',
                  allowOutsideClick: false
                }).then(() => {
                  window.location.href = 'signup/verify_otp.php';
                })
              } else if (data == 'admin') {
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  icon: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(() => {
                  window.location.href = 'admin/dashboard.php';
                })
              } else if (data == 'secretary') {
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  icon: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(() => {
                  window.location.href = 'secretary/dashboard.php';
                })
              } else if (data == 'resident') {
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  icon: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(() => {
                  window.location.href = 'resident/dashboard.php';
                })
              }
            }
          })
        }
      });

      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("fa-eye-slash").removeClass("fa-eye");
        } else {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("fa-eye-slash").addClass("fa-eye");
        }
      });
    });
  </script>
</body>

</html>