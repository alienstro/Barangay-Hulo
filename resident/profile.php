<?php

include_once '../connection.php';
session_start();


try {
  if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'resident') {

    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die($con->error);
    $stmt_user->bind_param('s', $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $username = $row_user['username'];
    $old_password = $row_user['password'];
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];


    $sql_resident = "SELECT * FROM residence_information WHERE residence_id = '$user_id'";
    $query_resident = $con->query($sql_resident) or die($con->error);
    $row_resident = $query_resident->fetch_assoc();


    if ($row_resident['image'] != '') {
      $iamge_resident = $row_resident['image_path'];
    } else {
      $iamge_resident = '../assets/dist/img/blank_image.png';
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
  } else {
    echo '<script>
          window.location.href = "../login.php";
        </script>';
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
  <title>Profile - Barangay Hulo</title>

  <link rel="preload" href="../assets/logo/cover.JPG" as="image">
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
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

    .content-wrapper {
      background-color: rgba(0, 0, 0, 0.40);
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      background-attachment: fixed;
      min-height: calc(100vh - 120px);
      padding: 40px 20px;
    }

    .profile-container {
      max-width: 800px;
      margin: 30px auto;
    }

    .profile-card {
      width: calc(100% - 48px);
      max-width: 760px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
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

    .profile-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 24px 20px;
      /* reduced vertical padding */
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .profile-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    .profile-image-container {
      position: relative;
      width: 120px;
      /* smaller image to reduce header height */
      height: 120px;
      margin: 0 auto 14px;
      z-index: 1;
    }

    .profile-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 5px solid white;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
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

    .resident-number {
      color: white;
      font-size: 16px;
      /* slightly smaller */
      font-weight: 600;
      position: relative;
      z-index: 1;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-body {
      padding: 28px 22px;
      /* reduced padding for compact card */
    }

    .section-title {
      font-size: 18px;
      /* slightly smaller */
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f0f0f0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #b30000;
      font-size: 22px;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .input-group {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      box-shadow: 0 4px 16px rgba(179, 0, 0, 0.2);
      transform: translateY(-2px);
    }

    .input-group-text {
      background: white;
      color: #b30000;
      font-size: 18px;
      border: none;
    }

    .form-control {
      border: none;
      padding: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .input-group-append .input-group-text {
      border-left: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .input-group-append .input-group-text:hover {
      background: #f8f9fa;
    }

    .input-group-append a {
      color: #666;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .input-group-append a:hover {
      color: #b30000;
    }

    .btn-submit {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 15px;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(179, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.2);
      transition: left 0.5s ease;
    }

    .btn-submit:hover::before {
      left: 100%;
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 25px rgba(179, 0, 0, 0.4);
    }

    .btn-submit:active {
      transform: translateY(-1px);
    }

    /* Footer */
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


    .invalid-feedback {
      color: #dc3545;
      font-size: 13px;
      margin-top: 5px;
    }

    .is-invalid {
      border-color: #dc3545 !important;
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

      .content-wrapper {
        padding: 30px 15px;
      }

      .profile-container {
        max-width: 700px;
        margin: 20px auto;
      }

      .profile-card {
        width: calc(100% - 30px);
      }
    }

    /* Mobile Styles (481px - 767px) */
    @media (max-width: 767px) {
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

      .profile-container {
        max-width: 100%;
        margin: 0;
      }

      .profile-card {
        width: 100%;
        max-width: 100%;
        border-radius: 16px;
      }

      .profile-header {
        padding: 24px 20px;
      }

      .profile-image-container {
        width: 110px;
        height: 110px;
        margin-bottom: 12px;
      }

      .profile-image {
        width: 110px;
        height: 110px;
        border: 4px solid white;
      }

      .resident-number {
        font-size: 15px;
      }

      .profile-body {
        padding: 24px 20px;
      }

      .section-title {
        font-size: 17px;
        margin-bottom: 18px;
      }

      .section-title i {
        font-size: 20px;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .input-group-text {
        font-size: 16px;
        padding: 10px 12px;
      }

      .form-control {
        font-size: 14px;
        padding: 10px 12px;
      }

      .btn-submit {
        font-size: 15px;
        padding: 13px;
      }

      footer.main-footer {
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

      .profile-card {
        border-radius: 14px;
      }

      .profile-header {
        padding: 20px 16px;
      }

      .profile-image-container {
        width: 100px;
        height: 100px;
        margin-bottom: 10px;
      }

      .profile-image {
        width: 100px;
        height: 100px;
        border: 3px solid white;
      }

      .resident-number {
        font-size: 14px;
      }

      .resident-number i {
        font-size: 13px;
      }

      .profile-body {
        padding: 20px 16px;
      }

      .section-title {
        font-size: 16px;
        margin-bottom: 16px;
        gap: 8px;
      }

      .section-title i {
        font-size: 18px;
      }

      .form-group {
        margin-bottom: 18px;
      }

      .input-group {
        border-radius: 10px;
      }

      .input-group-text {
        font-size: 15px;
        padding: 10px;
      }

      .form-control {
        font-size: 13px;
        padding: 10px;
      }

      .btn-submit {
        font-size: 14px;
        padding: 12px;
        border-radius: 10px;
        letter-spacing: 0.5px;
      }

      .invalid-feedback {
        font-size: 12px;
      }

      footer.main-footer {
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

      .profile-image-container {
        width: 90px;
        height: 90px;
      }

      .profile-image {
        width: 90px;
        height: 90px;
      }

      .resident-number {
        font-size: 13px;
      }

      .profile-body {
        padding: 18px 14px;
      }

      .section-title {
        font-size: 15px;
      }

      .form-control {
        font-size: 12px;
      }

      .btn-submit {
        font-size: 13px;
        padding: 11px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .content-wrapper {
        padding: 20px 15px;
        min-height: auto;
      }

      .profile-header {
        padding: 16px;
      }

      .profile-image-container {
        width: 80px;
        height: 80px;
        margin-bottom: 8px;
      }

      .profile-image {
        width: 80px;
        height: 80px;
        border: 3px solid white;
      }

      .resident-number {
        font-size: 13px;
      }

      .profile-body {
        padding: 20px 16px;
      }

      .form-group {
        margin-bottom: 14px;
      }

      .section-title {
        margin-bottom: 14px;
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
      .btn-submit,
      .input-group-append .input-group-text {
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

    <nav class="main-header navbar navbar-expand-md custom-navbar">
      <div class="container">
        <a href="dashboard.php" class="navbar-brand">
          <img src="../assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="brand-image">
          <span class="brand-text">Barangay Hulo</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link">
                <i class="fas fa-home"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a href="profile.php" class="nav-link active">
                <i class="fas fa-user-alt"></i> <?= $last_name_user ?>-<?= $user_id ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="../logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="content-wrapper">
      <div class="content">
        <div class="container">
          <div class="profile-container">
            <div class="profile-card">
              <div class="profile-header">
                <div class="profile-image-container">
                  <img src="<?= $iamge_resident ?>" alt="Resident Image" class="profile-image">
                </div>
                <div class="resident-number">
                  <i class="fas fa-id-card"></i> Resident ID: <?= $user_id ?>
                </div>
              </div>

              <div class="profile-body">
                <form id="changeProfile" method="post">
                  <div class="section-title">
                    <i class="fas fa-user-edit"></i>
                    <span>Account Settings</span>
                  </div>

                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fas fa-user"></i>
                        </span>
                      </div>
                      <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?= $username ?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="input-group" id="show_hide_password_old">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </span>
                      </div>
                      <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Current Password">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <a href="#"><i class="fas fa-eye-slash"></i></a>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="input-group" id="show_hide_password">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fas fa-key"></i>
                        </span>
                      </div>
                      <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <a href="#"><i class="fas fa-eye-slash"></i></a>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="input-group" id="show_hide_password_confirm">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="fas fa-check-circle"></i>
                        </span>
                      </div>
                      <input type="password" id="edit_confirm_password" name="edit_confirm_password" class="form-control" placeholder="Confirm New Password">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <a href="#"><i class="fas fa-eye-slash"></i></a>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group mb-0">
                    <button type="submit" class="btn btn-submit btn-block">
                      <i class="fas fa-save"></i> Update Profile
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
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
                <img src="../assets/logo/google_maps.png" alt="Barangay Hulo Map" class="map-thumbnail">
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

  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="../assets/dist/js/adminlte.js"></script>
  <script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
  <script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {


      $(function() {
        $.validator.setDefaults({
          submitHandler: function(form) {

            var newPassword = $("#new_password").val();
            var edit_confirm_password = $("#edit_confirm_password").val();

            if (newPassword != edit_confirm_password) {



              Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                icon: 'error',
                html: '<b>New password and confirm password do not match<b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              })



            } else {


              $.ajax({
                url: 'changeProfile.php',
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {

                  if (data == 'error1') {
                    Swal.fire({
                      title: '<strong class="text-danger">ERROR</strong>',
                      icon: 'error',
                      html: '<b>Username already exists<b>',
                      width: '400px',
                      confirmButtonColor: '#b30000',
                    })
                  } else if (data == 'error2') {

                    Swal.fire({
                      title: '<strong class="text-danger">ERROR</strong>',
                      icon: 'error',
                      html: '<b>Current password is incorrect<b>',
                      width: '400px',
                      confirmButtonColor: '#b30000',
                    })

                  } else {

                    Swal.fire({
                      title: '<strong class="text-success">SUCCESS</strong>',
                      icon: 'success',
                      html: '<b>Profile updated successfully<b>',
                      width: '400px',
                      confirmButtonColor: '#b30000',
                      allowOutsideClick: false,
                      showConfirmButton: false,
                      timer: 2000,
                    }).then(() => {
                      $("#old_password").val('');
                      $("#new_password").val('');
                      $("#edit_confirm_password").val('');



                    })



                  }

                }
              }).fail(function() {
                Swal.fire({
                  title: '<strong class="text-danger">Oops...</strong>',
                  icon: 'error',
                  html: '<b>Something went wrong!<b>',
                  width: '400px',
                  confirmButtonColor: '#b30000',
                })
              })

            }




          }
        });
        $('#changeProfile').validate({

          rules: {
            username: {
              required: true,
              minlength: 6
            },
            old_password: {
              required: true,

            },
            new_password: {
              minlength: 6

            },



          },
          messages: {
            username: {
              required: "This field is required",
              minlength: "Username must be at least 6 characters"
            },
            old_password: {
              required: "This field is required",

            },
            new_password: {
              minlength: "Password must be at least 6 characters",

            },




          },


          errorElement: 'span',
          errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);

          },
          highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          },

        });

      })


      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("fa-eye-slash");
          $('#show_hide_password i').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("fa-eye-slash");
          $('#show_hide_password i').addClass("fa-eye");
        }
      });
      $("#show_hide_password_confirm a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password_confirm input').attr("type") == "text") {
          $('#show_hide_password_confirm input').attr('type', 'password');
          $('#show_hide_password_confirm i').addClass("fa-eye-slash");
          $('#show_hide_password_confirm i').removeClass("fa-eye");
        } else if ($('#show_hide_password_confirm input').attr("type") == "password") {
          $('#show_hide_password_confirm input').attr('type', 'text');
          $('#show_hide_password_confirm i').removeClass("fa-eye-slash");
          $('#show_hide_password_confirm i').addClass("fa-eye");
        }
      });
      $("#show_hide_password_old a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password_old input').attr("type") == "text") {
          $('#show_hide_password_old input').attr('type', 'password');
          $('#show_hide_password_old i').addClass("fa-eye-slash");
          $('#show_hide_password_old i').removeClass("fa-eye");
        } else if ($('#show_hide_password_old input').attr("type") == "password") {
          $('#show_hide_password_old input').attr('type', 'text');
          $('#show_hide_password_old i').removeClass("fa-eye-slash");
          $('#show_hide_password_old i').addClass("fa-eye");
        }
      });
    })
  </script>


</body>

</html>