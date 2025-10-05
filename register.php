<?php
//index.php
include_once 'connection.php';
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {


  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $query = $con->query($sql) or die($con->error);
  $row = $query->fetch_assoc();
  $account_type = $row['user_type'];
  if ($account_type == 'admin') {
    echo '<script>
          window.location.href="admin/dashboard.php";
      </script>';
  } elseif ($account_type == 'secretary') {
    echo '<script>
          window.location.href="secretary/dashboard.php";
      </script>';
  } else {
    echo '<script>
      window.location.href="resident/dashboard.php";
  </script>';
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



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - Register</title>
  <link rel="preload" href="assets/logo/cover.JPG" as="image" fetchpriority="high">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/bs-stepper/css/bs-stepper.min.css">
  <link rel="stylesheet" href="assets/plugins/phone code/intlTelInput.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <link rel="stylesheet" href="assets/plugins/step-wizard/css/smart_wizard_all.min.css">
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

    .nav-link {
      color: #333 !important;
      font-weight: 500;
      padding: 8px 16px !important;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin: 0 4px;
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
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      min-height: calc(100vh - 120px);
      padding: 60px 0;
    }

    .register-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .register-card {
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      border: none;
      animation: fadeInUp 0.6s ease;
      background: white;
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

    .register-header {
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
      width: 100px;
      height: 100px;
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

    .register-title {
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

    .register-body {
      background: white;
      padding: 36px 32px;
    }

    .profile-section {
      text-align: center;
      padding-bottom: 24px;
      border-bottom: 2px solid #f0f0f0;
      margin-bottom: 24px;
    }

    .profile-image-container {
      position: relative;
      width: 150px;
      height: 150px;
      margin: 0 auto 16px;
      border-radius: 50%;
      overflow: hidden;
      border: 5px solid #b30000;
      box-shadow: 0 8px 24px rgba(179, 0, 0, 0.3);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .profile-image-container:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 32px rgba(179, 0, 0, 0.4);
    }

    .profile-image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-username {
      font-size: 20px;
      font-weight: 600;
      color: #b30000;
      text-align: center;
      margin-bottom: 0;
      min-height: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 500;
      color: #333;
      margin-bottom: 8px;
      font-size: 14px;
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
      font-size: 16px;
      padding: 12px 16px;
    }

    .input-group-account {
      border: 1px solid #ddd;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .input-group-account:focus-within {
      box-shadow: 0 4px 16px rgba(179, 0, 0, 0.2);
      transform: translateY(-2px);
    }

    .form-control,
    .form-control:focus {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 0 16px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #b30000;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    select.form-control {
      cursor: pointer;
    }

    .nav-tabs {
      border-bottom: 2px solid #b30000;
    }

    .nav-tabs .nav-link {
      color: #666 !important;
      font-weight: 500;
      border: none;
      border-radius: 0;
      padding: 12px 24px;
      transition: all 0.3s ease;
      background: transparent !important;
      transform: none !important;
      margin: 0 !important;
    }

    .nav-tabs .nav-link:hover {
      color: #b30000 !important;
      background: rgba(179, 0, 0, 0.05) !important;
    }

    .nav-tabs .nav-link.active {
      color: white !important;
      background: #b30000 !important;
    }

    .tab-content {
      padding-top: 24px;
    }

    .lead {
      font-size: 20px;
      font-weight: 600;
      color: #b30000;
      margin-bottom: 24px;
    }

    .btn-register {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      padding: 14px 32px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
      background: linear-gradient(135deg, #8b0000 0%, #b30000 100%);
      color: white;
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

    .toggle-password {
      position: absolute;
      top: 42px;
      right: 12px;
      color: #b30000;
      cursor: pointer;
      font-size: 16px;
    }

    .toggle-password:hover {
      color: #8b0000;
    }

    @media (max-width: 768px) {
      .register-container {
        margin: 20px;
      }

      .register-body {
        padding: 28px 24px;
      }

      .logo-main {
        width: 80px;
        height: 80px;
      }

      .register-title {
        font-size: 22px;
      }

      .content-wrapper {
        padding: 40px 0;
      }

      .profile-image-container {
        width: 120px;
        height: 120px;
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
              <a href="register.php" class="nav-link active">
                <i class="fas fa-user-plus"></i> Register
              </a>
            </li>
            <li class="nav-item">
              <a href="login.php" class="nav-link">
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
        <div class="container register-container">
          <form id="registerResidentForm" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="register-card">
              <!-- Profile Section -->
              <div class="register-body">
                <div class="profile-section">
                  <label for="add_image_residence" class="profile-image-container" id="image_residence">
                    <img src="assets/dist/img/blank_image.png" alt="User profile picture">
                  </label>
                  <input type="file" name="add_image_residence" id="add_image_residence" style="display: none;">
                  <h3 class="profile-username">
                    <span id="keyup_first_name"></span> <span id="keyup_last_name"></span>
                  </h3>
                </div>

                <!-- Tabs -->
                <div class="card-header p-0 pt-1" style="background: white; border: none;">
                  <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="basic-info-tab" data-toggle="pill" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true">Basic Info</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="personal-tab" data-toggle="pill" href="#personal" role="tab" aria-controls="personal" aria-selected="false">Personal</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="other-info-tab" data-toggle="pill" href="#other-info" role="tab" aria-controls="other-info" aria-selected="false">Address</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="guardian-tab" data-toggle="pill" href="#guardian" role="tab" aria-controls="guardian" aria-selected="false">Guardian</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="false">Account</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <!-- Basic Info Tab -->
                  <div class="tab-pane fade active show" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                    <p class="lead text-center">Personal Details</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>First Name</label>
                          <input type="text" class="form-control" id="add_first_name" name="add_first_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Middle Name</label>
                          <input type="text" class="form-control" id="add_middle_name" name="add_middle_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Last Name</label>
                          <input type="text" class="form-control" id="add_last_name" name="add_last_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Suffix</label>
                          <input type="text" class="form-control" id="add_suffix" name="add_suffix">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Civil Status</label>
                          <select name="add_civil_status" id="add_civil_status" class="form-control">
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Religion</label>
                          <input type="text" class="form-control" id="add_religion" name="add_religion">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Nationality</label>
                          <input type="text" class="form-control" id="add_nationality" name="add_nationality">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Personal Tab -->
                  <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <p class="lead text-center">Personal Information</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Voters</label>
                          <select name="add_voters" id="add_voters" class="form-control">
                            <option value=""></option>
                            <option value="NO">NO</option>
                            <option value="YES">YES</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Gender</label>
                          <select name="add_gender" id="add_gender" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Date of Birth</label>
                          <input type="date" class="form-control" id="add_birth_date" name="add_birth_date">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Place of Birth</label>
                          <input type="text" class="form-control" id="add_birth_place" name="add_birth_place">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>PWD</label>
                          <select name="add_pwd" id="add_pwd" class="form-control">
                            <option value=""></option>
                            <option value="NO">NO</option>
                            <option value="YES">YES</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group" id="pwd_check" style="display: none;">
                          <label>TYPE OF PWD</label>
                          <select class="form-control" id="add_pwd_info" name="add_pwd_info">
                            <option value="">Select Type</option>
                            <option value="Psychosocial disability">Psychosocial disability</option>
                            <option value="Disability caused by chronic illness">Disability caused by chronic illness</option>
                            <option value="Learning disability">Learning disability</option>
                            <option value="Mental disability">Mental disability</option>
                            <option value="Visual disability">Visual disability</option>
                            <option value="Orthopedic disability">Orthopedic disability</option>
                            <option value="Communication disability">Communication disability</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Single Parent</label>
                          <select name="add_single_parent" id="add_single_parent" class="form-control">
                            <option value=""></option>
                            <option value="NO">NO</option>
                            <option value="YES">YES</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Other Info Tab -->
                  <div class="tab-pane fade" id="other-info" role="tabpanel" aria-labelledby="other-info-tab">
                    <p class="lead text-center">Address & Contact</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Municipality</label>
                          <input type="text" class="form-control" id="add_municipality" name="add_municipality">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Zip</label>
                          <input type="text" class="form-control" id="add_zip" name="add_zip">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Barangay</label>
                          <input type="text" class="form-control" id="add_barangay" name="add_barangay">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>House Number</label>
                          <input type="text" class="form-control" id="add_house_number" name="add_house_number">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Street</label>
                          <input type="text" class="form-control" id="add_street" name="add_street">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Address</label>
                          <input type="text" class="form-control" id="add_address" name="add_address">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Contact Number</label>
                          <input type="text" maxlength="11" class="form-control" id="add_contact_number" name="add_contact_number">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email Address</label>
                          <input type="email" class="form-control" id="add_email_address" name="add_email_address">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Guardian Tab -->
                  <div class="tab-pane fade" id="guardian" role="tabpanel" aria-labelledby="guardian-tab">
                    <p class="lead text-center">Guardian Information</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Father's Name</label>
                          <input type="text" class="form-control" id="add_fathers_name" name="add_fathers_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Mother's Name</label>
                          <input type="text" class="form-control" id="add_mothers_name" name="add_mothers_name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Guardian</label>
                          <input type="text" class="form-control" id="add_guardian" name="add_guardian">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Guardian Contact</label>
                          <input type="text" class="form-control" maxlength="11" id="add_guardian_contact" name="add_guardian_contact">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Account Tab -->
                  <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <p class="lead text-center">Account Information</p>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label>Username</label>
                          <input type="text" id="add_username" name="add_username" class="form-control">
                        </div>
                      </div>

                      <div class="col-12">
                        <div class="form-group position-relative">
                          <label>Password</label>
                          <input type="password" id="add_password" name="add_password" class="form-control" style="padding-right:44px;">
                          <i class="fas fa-eye-slash toggle-password" id="toggle_password"></i>
                        </div>
                      </div>

                      <div class="col-12">
                        <div class="form-group position-relative">
                          <label>Confirm Password</label>
                          <input type="password" id="add_confirm_password" name="add_confirm_password" class="form-control" style="padding-right:44px;">
                          <i class="fas fa-eye-slash toggle-password" id="toggle_confirm_password"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer" style="background: white; border: none; padding: 24px 32px;">
                <button type="submit" class="btn btn-register">
                  <i class="fas fa-user-plus"></i> Register
                </button>
                <div class="text-center mt-3">
                  <span style="color: #666; font-size: 14px;">Already have an account? </span>
                  <a href="login.php" style="color: #b30000; font-weight: 500; text-decoration: none;">Login here</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <footer class="main-footer footer-custom">
      <i class="fas fa-map-marker-alt"></i> 91 Coronado, Barangay Hulo, Mandaluyong, Philippines
    </footer>
  </div>





  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/dist/js/adminlte.js"></script>
  <script src="assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="assets/plugins/jquery-validation/additional-methods.min.js"></script>
  <script src="assets/plugins/phone code/intlTelInput.js"></script>
  <script src="assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/step-wizard/js/jquery.smartWizard.min.js"></script>
  <script>
    $(document).ready(function() {

      $("#add_pwd").change(function() {
        var pwd_check = $(this).val();

        if (pwd_check == 'YES') {
          $("#pwd_check").css('display', 'block');
          $("#add_pwd_info").prop('disabled', false);
        } else {
          $("#pwd_check").css('display', 'none');
          $("#add_pwd_info").prop('disabled', true);
        }

      })
      $(function() {
        $.validator.setDefaults({
          submitHandler: function(form) {
            const formData = new FormData(form);
            
            // Debug: Log FormData contents to check if file is included
            for (let [key, value] of formData.entries()) {
              console.log(key, value);
            }
            
            // Check if image file is present
            if (!formData.has('add_image_residence') || formData.get('add_image_residence').size === 0) {
              Swal.fire({
                title: '<strong class="text-warning">Warning</strong>',
                icon: 'warning',
                html: '<b>No image selected. Please upload a profile picture.</b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              });
              return; // Prevent submission if no image
            }

            $.ajax({
              url: 'signup/newResidence.php',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              cache: false,
              success: function(data) {

                if (data == 'errorPassword') {
                  Swal.fire({
                    title: '<strong class="text-danger">ERROR</strong>',
                    type: 'error',
                    html: '<b>Password not Match<b>',
                    width: '400px',
                    confirmButtonColor: '#b30000',
                  })
                } else if (data == 'errorUsername') {

                  Swal.fire({
                    title: '<strong class="text-danger">ERROR</strong>',
                    type: 'error',
                    html: '<b>Username is Already Taken<b>',
                    width: '400px',
                    confirmButtonColor: '#6610f2',
                  })

                } else {

                  Swal.fire({
                    title: '<strong class="text-success">SUCCESS</strong>',
                    type: 'success',
                    html: '<b>Registered Residence has Successfully<b>',
                    width: '400px',
                    confirmButtonColor: '#6610f2',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2000,
                  }).then(() => {
                    window.location.reload();
                  })
                }


              }
            }).fail(function() {
              Swal.fire({
                title: '<strong class="text-danger">Ooppss..</strong>',
                type: 'error',
                html: '<b>Something went wrong with ajax !<b>',
                width: '400px',
                confirmButtonColor: '#6610f2',
              })
            })


          }
        });
        $('#registerResidentForm').validate({
          ignore: '',
          rules: {
            // Basic Info
            add_first_name: {
              required: true,
              minlength: 2
            },
            add_middle_name: {
              required: false,
              minlength: 0
            },
            add_last_name: {
              required: true,
              minlength: 2
            },
            add_suffix: {
              required: false
            },
            add_civil_status: {
              required: true
            },
            add_religion: {
              required: false
            },
            add_nationality: {
              required: false
            },

            // Personal
            add_voters: {
              required: true
            },
            add_gender: {
              required: true
            },
            add_birth_date: {
              required: true,
              date: true
            },
            add_birth_place: {
              required: false
            },
            add_pwd: {
              required: true
            },
            add_pwd_info: {
              required: function() {
                return $("#add_pwd").val() === 'YES';
              }
            },
            add_single_parent: {
              required: true
            },

            // Address & Contact
            add_municipality: {
              required: true
            },
            add_zip: {
              required: true,
              digits: true,
              minlength: 4
            },
            add_barangay: {
              required: true
            },
            add_house_number: {
              required: false
            },
            add_street: {
              required: false
            },
            add_address: {
              required: true
            },
            add_contact_number: {
              required: true,
              digits: true,
              minlength: 11
            },
            add_email_address: {
              required: false,
              email: true
            },

            // Guardian
            add_fathers_name: {
              required: false
            },
            add_mothers_name: {
              required: false
            },
            add_guardian: {
              required: false
            },
            add_guardian_contact: {
              required: false,
              digits: true,
              minlength: 11,
              maxlength: 11
            },

            // Account
            add_username: {
              required: true,
              minlength: 8
            },
            add_password: {
              required: true,
              minlength: 8
            },
            add_confirm_password: {
              required: true,
              minlength: 8,
              equalTo: "#add_password"
            }
          },
          messages: {
            add_first_name: {
              required: "This field is required",
              minlength: "At least 2 characters"
            },
            add_last_name: {
              required: "This field is required",
              minlength: "At least 2 characters"
            },
            add_civil_status: {
              required: "This field is required"
            },
            add_voters: {
              required: "This field is required"
            },
            add_gender: {
              required: "This field is required"
            },
            add_birth_date: {
              required: "This field is required",
              date: "Enter a valid date"
            },
            add_pwd: {
              required: "This field is required"
            },
            add_pwd_info: {
              required: "Provide PWD type"
            },
            add_single_parent: {
              required: "This field is required"
            },
            add_municipality: {
              required: "This field is required"
            },
            add_zip: {
              required: "This field is required",
              digits: "Only numbers allowed",
              minlength: "Enter a valid ZIP"
            },
            add_barangay: {
              required: "This field is required"
            },
            add_address: {
              required: "This field is required"
            },
            add_contact_number: {
              required: "This field is required",
              digits: "Only numbers allowed",
              minlength: "Enter exact contact number (e.g 09xxxxxxxxx)"
            },
            add_email_address: {
              email: "Enter a valid email address"
            },
            add_guardian_contact: {
              digits: "Only numbers allowed",
              minlength: "Enter exact contact number (e.g 09xxxxxxxxx)",
              maxlength: "Enter exact contact number (e.g 09xxxxxxxxx)"
            },
            add_username: {
              required: "This field is required",
              minlength: "At least 8 characters"
            },
            add_password: {
              required: "This field is required",
              minlength: "At least 8 characters"
            },
            add_confirm_password: {
              required: "This field is required",
              minlength: "At least 8 characters",
              equalTo: "Passwords do not match"
            }
          },
          errorElement: 'span',
          errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            if (element.parent('.input-group').length) {
              element.parent('.input-group').after(error);
            } else {
              element.closest('.form-group').append(error);
            }
          },
          highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            if ($(element).parent('.input-group').length) {
              $(element).parent('.input-group').addClass('is-invalid');
            }
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            if ($(element).parent('.input-group').length) {
              $(element).parent('.input-group').removeClass('is-invalid');
            }
          },
        });

      })

      $("#toggle_password").on('click', function() {
        let input = $('#add_password');
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
          input.attr("type", "password");
          $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        }
      });

      $("#toggle_confirm_password").on('click', function() {
        let input = $('#add_confirm_password');
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
          input.attr("type", "password");
          $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        }
      });


      $("#image_residence").on('click', function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('add_image_residence');
        if (fileInput) fileInput.click();
      });


      function displayImage(input) {
        console.log("working")

        if (input.files && input.files[0]) {
          const file = input.files[0];
          const fileExtension = file.name.split('.').pop().toLowerCase();

          if (!['gif', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
            Swal.fire({
              title: '<strong class="text-danger">ERROR</strong>',
              icon: 'error',
              html: '<b>Invalid Image File</b>',
              width: '400px',
              confirmButtonColor: '#b30000',
            });
            $("#add_image_residence").val('');
            $("#image_residence img").attr('src', 'assets/dist/img/blank_image.png');
            return;
          }

          const reader = new FileReader();
          reader.onload = function(e) {
            $("#image_residence img").attr('src', e.target.result).hide().fadeIn(650);
          };
          reader.readAsDataURL(file);
        }
      }

      $("#add_image_residence").change(function() {
        displayImage(this);
      });

      // Update profile username display on keyup
      $("#add_first_name").keyup(function() {
        var first_name = $(this).val();
        $("#keyup_first_name").text(first_name);
      });

      $("#add_last_name").keyup(function() {
        var last_name = $(this).val();
        $("#keyup_last_name").text(last_name);
      });




    });
  </script>

  <script>
    (function($) {
      $.fn.inputFilter = function(inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
          if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
          } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          } else {
            this.value = "";
          }
        });
      };
    }(jQuery));


    $("#add_contact_number,#add_zip, #add_guardian_contact, #add_age").inputFilter(function(value) {
      return /^-?\d*$/.test(value);

    });


    $("#add_first_name, #add_middle_name, #add_last_name, #add_suffix, #add_religion, #add_nationality, #add_municipality, #add_fathers_name, #add_mothers_name, #add_guardian").inputFilter(function(value) {
      return /^[a-z, ]*$/i.test(value);
    });

    $("#add_street, #add_birth_place, #add_house_number").inputFilter(function(value) {
      return /^[0-9a-z, ,-]*$/i.test(value);
    });
  </script>

</body>

</html>