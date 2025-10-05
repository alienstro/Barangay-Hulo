<?php 

include_once '../connection.php';
session_start();


try{
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'resident'){

    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];


    $sql_resident = "SELECT residence_information.*, residence_status.* FROM residence_information
    INNER JOIN residence_status ON residence_information.residence_id = residence_status.residence_id
     WHERE residence_information.residence_id = '$user_id'";
    $query_resident = $con->query($sql_resident) or die ($con->error);
    $row_resident = $query_resident->fetch_assoc();



    $sql = "SELECT * FROM `barangay_information`";
    $query = $con->prepare($sql) or die ($con->error);
    $query->execute();
    $result = $query->get_result();
    while($row = $result->fetch_assoc()){
        $barangay = $row['barangay'];
        $zone = $row['zone'];
        $district = $row['district'];
        $image = $row['image'];
        $image_path = $row['image_path'];
        $id = $row['id'];
        $postal_address = $row['postal_address'];
    }


  }else{
   echo '<script>
          window.location.href = "../login.php";
        </script>';
  }

}catch(Exception $e){
  echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - My Information</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
      padding: 60px 20px;
    }

    .info-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .info-card {
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

    .info-header {
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
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    .info-title {
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

    .info-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 16px;
      margin-top: 8px;
    }

    .info-body {
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
      margin-bottom: 8px;
    }

    .profile-id {
      font-size: 14px;
      color: #666;
      font-weight: 500;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-control {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 0px 16px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #b30000;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
      background-color: white;
    }

    select.form-control {
      cursor: pointer;
    }

    .section-title {
      font-size: 18px;
      font-weight: 600;
      color: #b30000;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #b30000;
    }

    .btn-update {
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

    .btn-update:hover {
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

      .content-wrapper {
        padding: 40px 15px;
      }

      .info-container {
        max-width: 1000px;
      }

      .info-body {
        padding: 32px 28px;
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

      .info-container {
        max-width: 100%;
        margin: 0;
      }

      .info-card {
        border-radius: 20px;
      }

      .info-header {
        padding: 28px 20px;
      }

      .logo-main {
        width: 90px;
        height: 90px;
        border: 4px solid white;
      }

      .info-title {
        font-size: 24px;
      }

      .info-subtitle {
        font-size: 15px;
      }

      .info-body {
        padding: 28px 20px;
      }

      .profile-section {
        padding-bottom: 20px;
        margin-bottom: 20px;
      }

      .profile-image-container {
        width: 130px;
        height: 130px;
        border: 4px solid #b30000;
      }

      .profile-username {
        font-size: 18px;
      }

      .profile-id {
        font-size: 13px;
      }

      .section-title {
        font-size: 17px;
        margin-bottom: 18px;
      }

      .form-group label {
        font-size: 11px;
      }

      .form-control {
        font-size: 13px;
        padding: 8px 14px;
      }

      .row {
        margin-left: 0;
        margin-right: 0;
      }

      .row > [class*='col-'] {
        padding-left: 8px;
        padding-right: 8px;
      }

      .btn-update {
        font-size: 15px;
        padding: 12px 28px;
      }

      .card-footer {
        padding: 20px 20px !important;
      }

      footer.footer-custom {
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

      .info-card {
        border-radius: 16px;
      }

      .info-header {
        padding: 24px 16px;
      }

      .logo-main {
        width: 80px;
        height: 80px;
        border: 3px solid white;
      }

      .info-title {
        font-size: 22px;
        letter-spacing: 0.5px;
      }

      .info-subtitle {
        font-size: 14px;
      }

      .info-body {
        padding: 24px 16px;
      }

      .profile-section {
        padding-bottom: 18px;
        margin-bottom: 18px;
      }

      .profile-image-container {
        width: 120px;
        height: 120px;
        border: 3px solid #b30000;
      }

      .profile-username {
        font-size: 17px;
        margin-bottom: 6px;
      }

      .profile-id {
        font-size: 12px;
      }

      .section-title {
        font-size: 16px;
        margin-bottom: 16px;
        padding-bottom: 8px;
      }

      .form-group {
        margin-bottom: 16px;
      }

      .form-group label {
        font-size: 10px;
        margin-bottom: 6px;
      }

      .form-control {
        font-size: 12px;
        padding: 8px 12px;
        border-radius: 10px;
      }

      .row > [class*='col-'] {
        padding-left: 6px;
        padding-right: 6px;
      }

      .btn-update {
        font-size: 14px;
        padding: 11px 24px;
        border-radius: 10px;
        letter-spacing: 0.5px;
      }

      .card-footer {
        padding: 18px 16px !important;
      }

      footer.footer-custom {
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

      .info-header {
        padding: 20px 14px;
      }

      .logo-main {
        width: 70px;
        height: 70px;
      }

      .info-title {
        font-size: 20px;
      }

      .info-subtitle {
        font-size: 13px;
      }

      .info-body {
        padding: 20px 14px;
      }

      .profile-image-container {
        width: 110px;
        height: 110px;
      }

      .profile-username {
        font-size: 16px;
      }

      .section-title {
        font-size: 15px;
      }

      .form-control {
        font-size: 11px;
        padding: 7px 10px;
      }

      .btn-update {
        font-size: 13px;
        padding: 10px 20px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .content-wrapper {
        padding: 20px 15px;
        min-height: auto;
      }

      .info-header {
        padding: 16px;
      }

      .logo-main {
        width: 60px;
        height: 60px;
        border: 3px solid white;
      }

      .logo-container {
        margin-bottom: 12px;
      }

      .info-title {
        font-size: 20px;
      }

      .info-subtitle {
        font-size: 13px;
        margin-top: 4px;
      }

      .info-body {
        padding: 20px 16px;
      }

      .profile-section {
        padding-bottom: 14px;
        margin-bottom: 14px;
      }

      .profile-image-container {
        width: 100px;
        height: 100px;
      }

      .section-title {
        margin-bottom: 12px;
        padding-bottom: 6px;
      }

      .form-group {
        margin-bottom: 12px;
      }

      @keyframes float {
        0%, 100% {
          transform: translateY(0);
        }
        50% {
          transform: translateY(-5px);
        }
      }
    }

    /* Single column layout for very small screens */
    @media (max-width: 575px) {
      .row > .col-md-6,
      .row > .col-md-4,
      .row > .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }

    /* Ensure proper touch targets on mobile */
    @media (hover: none) and (pointer: coarse) {
      .nav-link,
      .btn-update,
      .profile-image-container {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .form-control,
      select.form-control {
        min-height: 44px;
      }
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">

<div class="wrapper">

  <!-- Navbar -->
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
            <a href="profile.php" class="nav-link">
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
  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container info-container">
        <form id="editResidenceForm" method="POST" enctype="multipart/form-data" autocomplete="off">
          <div class="info-card">
            <!-- Header -->
            <div class="info-header">
              <div class="logo-container">
                <img src="../assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="logo-main">
              </div>
              <h1 class="info-title">My Information</h1>
              <p class="info-subtitle">Update your personal details</p>
            </div>

            <!-- Body -->
            <div class="info-body">
              <!-- Profile Section -->
              <div class="profile-section">
                <div class="profile-image-container" id="display_edit_image_residence_container">
                  <?php 
                    if($row_resident['image_path'] != '' || $row_resident['image_path'] != null || !empty($row_resident['image_path'])){
                      echo '<img src="'.$row_resident['image_path'].'" alt="User profile picture" id="display_edit_image_residence">';
                    }else{
                      echo '<img src="../assets/dist/img/blank_image.png" alt="User profile picture" id="display_edit_image_residence">';
                    }
                  ?>
                </div>
                <input type="file" name="edit_image_residence" id="edit_image_residence" style="display: none;">
                <input type="hidden" name="edit_residence_id" value="<?= $row_resident['residence_id'];?>">
                <h3 class="profile-username">
                  <?= $row_resident['first_name'] ?> <?= $row_resident['middle_name'] ?> <?= $row_resident['last_name'] ?>
                </h3>
                <p class="profile-id">RESIDENT NO. <?= $row_resident['residence_id'] ?></p>
              </div>

              <!-- Basic Information -->
              <div class="section-title">Basic Information</div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" value="<?= $row_resident['first_name'] ?>" id="edit_first_name" name="edit_first_name">
                    <input type="hidden" value="false" id="edit_first_name_check">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" class="form-control" value="<?= $row_resident['middle_name'] ?>" id="edit_middle_name" name="edit_middle_name">
                    <input type="hidden" id="edit_middle_name_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" value="<?= $row_resident['last_name'] ?>" id="edit_last_name" name="edit_last_name">
                    <input type="hidden" value="false" id="edit_last_name_check">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Suffix</label>
                    <input type="text" class="form-control" value="<?= $row_resident['suffix'] ?>" id="edit_suffix" name="edit_suffix">
                    <input type="hidden" id="edit_suffix_check" value="false">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Gender</label>
                    <select name="edit_gender" id="edit_gender" class="form-control">
                      <option value="Male" <?= $row_resident['gender'] == 'Male'? 'selected': '' ?>>Male</option>
                      <option value="Female" <?= $row_resident['gender'] == 'Female'? 'selected': '' ?>>Female</option>
                    </select>
                    <input type="hidden" id="edit_gender_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Civil Status</label>
                    <select name="edit_civil_status" id="edit_civil_status" class="form-control">
                      <option value="Single" <?= $row_resident['civil_status'] == 'Single'? 'selected': ''; ?>>Single</option>
                      <option value="Married" <?= $row_resident['civil_status'] == 'Married'? 'selected': ''; ?>>Married</option>
                    </select>
                    <input type="hidden" id="edit_civil_status_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Voters</label>
                    <select name="edit_voters" id="edit_voters" class="form-control">
                      <option value="NO" <?= $row_resident['voters'] == 'NO'? 'selected': '' ?>>NO</option>
                      <option value="YES" <?= $row_resident['voters'] == 'YES'? 'selected': '' ?>>YES</option>
                    </select>
                    <input type="hidden" value="false" id="edit_voters_check">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Single Parent</label>
                    <select name="edit_single_parent" id="edit_single_parent" class="form-control">
                      <option value="YES" <?= $row_resident['single_parent'] == 'YES'? 'selected': '' ?>>YES</option>
                      <option value="NO" <?= $row_resident['single_parent'] == 'NO'? 'selected': '' ?>>NO</option>
                    </select>
                    <input type="hidden" id="edit_single_parent_check" value="false">
                  </div>
                </div>
              </div>

              <!-- Personal Details -->
              <div class="section-title">Personal Details</div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" value="<?php echo strftime('%Y-%m-%d',strtotime($row_resident['birth_date'])); ?>" name="edit_birth_date" id="edit_birth_date"/>
                    <input type="hidden" id="edit_birth_date_check" value='false'>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Place of Birth</label>
                    <input type="text" class="form-control" value="<?= $row_resident['birth_place'] ?>" name="edit_birth_place" id="edit_birth_place">
                    <input type="hidden" id="edit_birth_place_check" value="false">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Age</label>
                    <input type="text" class="form-control" value="<?= $row_resident['age'] ?>" name="edit_age" id="edit_age" readonly>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Religion</label>
                    <input type="text" class="form-control" value="<?= $row_resident['religion'] ?>" name="edit_religion" id="edit_religion">
                    <input type="hidden" id="edit_religion_check" value="false">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" class="form-control" value="<?= $row_resident['nationality'] ?>" name="edit_nationality" id="edit_nationality">
                    <input type="hidden" id="edit_nationality_check" value="false">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>PWD</label>
                    <select name="edit_pwd" id="edit_pwd" class="form-control">
                      <option value="YES" <?= $row_resident['pwd'] == 'YES'? 'selected': '' ?>>YES</option>
                      <option value="NO" <?= $row_resident['pwd'] == 'NO'? 'selected': '' ?>>NO</option>
                    </select>
                    <input type="hidden" id="edit_pwd_check" value="false">
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <label>Type of PWD</label>
                    <select class="form-control" name="edit_pwd_info" id="edit_pwd_info" <?= $row_resident['pwd'] == 'NO' || $row_resident['pwd_info'] == ''? 'disabled': '' ?>>
                      <option value="">Select Type</option>
                      <option value="Psychosocial disability" <?= $row_resident['pwd_info'] == 'Psychosocial disability'? 'selected': '' ?>>Psychosocial disability</option>
                      <option value="Disability caused by chronic illness" <?= $row_resident['pwd_info'] == 'Disability caused by chronic illness'? 'selected': '' ?>>Disability caused by chronic illness</option>
                      <option value="Learning disability" <?= $row_resident['pwd_info'] == 'Learning disability'? 'selected': '' ?>>Learning disability</option>
                      <option value="Mental disability" <?= $row_resident['pwd_info'] == 'Mental disability'? 'selected': '' ?>>Mental disability</option>
                      <option value="Visual disability" <?= $row_resident['pwd_info'] == 'Visual disability'? 'selected': '' ?>>Visual disability</option>
                      <option value="Orthopedic disability" <?= $row_resident['pwd_info'] == 'Orthopedic disability'? 'selected': '' ?>>Orthopedic disability</option>
                      <option value="Communication disability" <?= $row_resident['pwd_info'] == 'Communication disability'? 'selected': '' ?>>Communication disability</option>
                    </select>
                    <input type="hidden" id="edit_pwd_info_check" value="false">
                  </div>
                </div>
              </div>

              <!-- Address Information -->
              <div class="section-title">Address Information</div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Municipality</label>
                    <input type="text" class="form-control" value="<?= $row_resident['municipality'] ?>" name="edit_municipality" id="edit_municipality">
                    <input type="hidden" id="edit_municipality_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" class="form-control" value="<?= $row_resident['barangay'] ?>" name="edit_barangay" id="edit_barangay">
                    <input type="hidden" id="edit_barangay_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>ZIP Code</label>
                    <input type="text" class="form-control" value="<?= $row_resident['zip'] ?>" name="edit_zip" id="edit_zip">
                    <input type="hidden" id="edit_zip_check" value="false">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>House Number</label>
                    <input type="text" class="form-control" value="<?= $row_resident['house_number'] ?>" name="edit_house_number" id="edit_house_number">
                    <input type="hidden" id="edit_house_number_check" value="false">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Street</label>
                    <input type="text" class="form-control" value="<?= $row_resident['street'] ?>" name="edit_street" id="edit_street">
                    <input type="hidden" id="edit_street_check" value="false">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Complete Address</label>
                    <input type="text" class="form-control" value="<?= $row_resident['address'] ?>" name="edit_address" id="edit_address">
                    <input type="hidden" id="edit_address_check" value="false">
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="section-title">Contact Information</div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" class="form-control" value="<?= $row_resident['email_address'] ?>" name="edit_email_address" id="edit_email_address">
                    <input type="hidden" id="edit_email_address_check" value="false">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" maxlength="11" class="form-control" value="<?= $row_resident['contact_number'] ?>" name="edit_contact_number" id="edit_contact_number">
                    <input type="hidden" id="edit_contact_number_check" value="false">
                  </div>
                </div>
              </div>

              <!-- Guardian Information -->
              <div class="section-title">Guardian Information</div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Father's Name</label>
                    <input type="text" class="form-control" value="<?= $row_resident['fathers_name'] ?>" name="edit_fathers_name" id="edit_fathers_name">
                    <input type="hidden" id="edit_fathers_name_check" value="false">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Mother's Name</label>
                    <input type="text" class="form-control" value="<?= $row_resident['mothers_name'] ?>" name="edit_mothers_name" id="edit_mothers_name">
                    <input type="hidden" id="edit_mothers_name_check" value="false">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Guardian</label>
                    <input type="text" class="form-control" value="<?= $row_resident['guardian'] ?>" name="edit_guardian" id="edit_guardian">
                    <input type="hidden" id="edit_guardian_check" value="false">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Guardian Contact</label>
                    <input type="text" class="form-control" maxlength="11" value="<?= $row_resident['guardian_contact'] ?>" name="edit_guardian_contact" id="edit_guardian_contact">
                    <input type="hidden" id="edit_guardian_contact_check" value="false">
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="card-footer" style="background: white; border: none; padding: 24px 32px;">
              <button type="submit" class="btn btn-update">
                <i class="fas fa-save"></i> Update Information
              </button>
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
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.js"></script>
<script src="../assets/plugins/popper/umd/popper.min.js"></script>
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>
<script src="../assets/plugins/select2/js/select2.full.min.js"></script>
<script src="../assets/plugins/moment/moment.min.js"></script>
<script src="../assets/plugins/chart.js/Chart.min.js"></script>
<script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../assets/plugins/jquery-validation/jquery-validate.bootstrap-tooltip.min.js"></script>

<script>
  $(document).ready(function(){

   

    $(function () {


      $("#edit_pwd").change(function(){
        var edit_pwd_one = $(this).val();


        if(edit_pwd_one == 'YES'){
          $("#edit_pwd_info").prop('disabled', false)
        }else{
          $("#edit_pwd_info").prop('disabled', true)
        }


      })

           var edit_first_name = $("#edit_first_name").val();
            var edit_last_name = $("#edit_last_name").val();
            var edit_term_from = $("#edit_term_from").val();
            var edit_term_to = $("#edit_term_to").val();
            var edit_voters = $("#edit_voters").val();
            var edit_pwd = $("#edit_pwd").val();
            var edit_birth_date = $("#edit_birth_date").val();
            var edit_birth_place = $("#edit_birth_place").val();
            var edit_middle_name = $("#edit_middle_name").val();
            var edit_suffix = $("#edit_suffix").val();
            var edit_gender = $("#edit_gender").val();
            var edit_vivil_status = $("#edit_vivil_status").val();
            var edit_nationality = $("#edit_nationality").val();
            var edit_municipality = $("#edit_municipality").val();
            var edit_zip = $("#edit_zip").val();
            var edit_barangay = $("#edit_barangay").val();
            var edit_house_number = $("#edit_house_number").val();
            var edit_street = $("#edit_street").val();
            var edit_address = $("#edit_address").val();
            var edit_email_address = $("#edit_email_address").val();
            var edit_contact_number = $("#edit_contact_number").val();
            var edit_fathers_name = $("#edit_fathers_name").val();
            var edit_mothers_name = $("#edit_mothers_name").val();
            var edit_guardian = $("#edit_guardian").val();
            var edit_guardian_contact = $("#edit_guardian_contact").val();
            var edit_pwd_info = $("#edit_pwd_info").val();
            var edit_single_parent = $("#edit_single_parent").val();


            $("#edit_pwd_info").change(function(){

              var newPwdIfo = $(this).val();

              if(!(newPwdIfo == edit_pwd_info )){

                $("#edit_pwd_info_check").val('true');

              }else{

                $("#edit_pwd_info_check").val('false');
              }

            })

            $("#edit_single_parent").change(function(){

              var newSingleParent = $(this).val();

              if(!(newSingleParent == edit_single_parent )){

                $("#edit_single_parent_check").val('true');

              }else{

                $("#edit_single_parent_check").val('false');
              }

            })


            $("#edit_first_name").change(function(){

                var newFirstName = $(this).val();

                if(!(newFirstName == edit_first_name )){

                  $("#edit_first_name_check").val('true');

                }else{

                  $("#edit_first_name_check").val('false');
                }

            })



              $("#edit_last_name").change(function(){

                var newLastName = $(this).val();

                if(!(newLastName == edit_last_name )){

                  $("#edit_last_name_check").val('true');

                }else{

                  $("#edit_last_name_check").val('false');

                }

              })

          

                $("#edit_voters").change(function(){

                  var newVoters = $(this).val();

                  if(!(newVoters == edit_voters )){

                  $("#edit_voters_check").val('true');

                  }else{

                  $("#edit_voters_check").val('false');

                  }

                })

                $("#edit_pwd").change(function(){

                  var newPwd = $(this).val();

                  if(!(newPwd == edit_pwd )){

                  $("#edit_pwd_check").val('true');

                  }else{

                  $("#edit_pwd_check").val('false');

                  }

                })

                $("#edit_birth_date").change(function(){

                  var newBday = $(this).val();

                  if(!(newBday == edit_birth_date )){

                  $("#edit_birth_date_check").val('true');

                  }else{

                  $("#edit_birth_date_check").val('false');

                  }

                  // Calculate age dynamically
                  if(newBday) {
                    var today = new Date();
                    var birthDate = new Date(newBday);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    var monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                      age--;
                    }

                    $("#edit_age").val(age);
                  }

                })

                $("#edit_birth_place").change(function(){

                  var newBplace = $(this).val();

                  if(!(newBplace == edit_birth_place )){

                  $("#edit_birth_place_check").val('true');

                  }else{

                  $("#edit_birth_place_check").val('false');

                  }

                })

                $("#edit_middle_name").change(function(){

                  var newMiddleName = $(this).val();

                  if(!(newMiddleName == edit_middle_name )){

                  $("#edit_middle_name_check").val('true');

                  }else{

                  $("#edit_middle_name_check").val('false');

                  }

                })

                $("#edit_suffix").change(function(){

                  var new_suffix = $(this).val();

                  if(!(new_suffix == edit_suffix )){

                  $("#edit_suffix_check").val('true');

                  }else{

                  $("#edit_suffix_check").val('false');

                  }

                })

                $("#edit_gender").change(function(){

                  var newGender = $(this).val();

                  if(!(newGender == edit_gender )){

                  $("#edit_gender_check").val('true');

                  }else{

                    $("#edit_gender_check").val('false');

                  }

                })

                $("#edit_civil_status").change(function(){

                  var newCivil = $(this).val();

                  if(!(newCivil == edit_civil_status )){

                  $("#edit_civil_status_check").val('true');

                  }else{

                    $("#edit_civil_status_check").val('false');
                  }

                })


                $("#edit_religion").change(function(){

                  var newReligion = $(this).val();

                  if(!(newReligion == edit_religion )){

                  $("#edit_religion_check").val('true');

                  }else{

                    $("#edit_religion_check").val('false');
                  }

                  })


                $("#edit_nationality").change(function(){

                var newNationality = $(this).val();

                if(!(newNationality == edit_nationality )){

                $("#edit_nationality_check").val('true');

                }else{

                $("#edit_nationality_check").val('false');
                }

                })

                $("#edit_municipality").change(function(){

                var newMunicipality = $(this).val();

                if(!(newMunicipality == edit_municipality )){

                $("#edit_municipality_check").val('true');

                }else{

                $("#edit_municipality_check").val('false');
                }

                })



                $("#edit_zip").change(function(){

                var newZip = $(this).val();

                if(!(newZip == edit_zip )){

                $("#edit_zip_check").val('true');

                }else{

                $("#edit_zip_check").val('false');
                }

                })


                $("#edit_barangay").change(function(){

                var newBarangay = $(this).val();

                if(!(newBarangay == edit_barangay )){

                $("#edit_barangay_check").val('true');

                }else{

                $("#edit_barangay_check").val('false');
                }

                })

                $("#edit_house_number").change(function(){

                var newHnumber = $(this).val();

                if(!(newHnumber == edit_house_number )){

                $("#edit_house_number_check").val('true');

                }else{

                $("#edit_house_number_check").val('false');
                }

                })

                $("#edit_street").change(function(){

                var newStreet = $(this).val();

                if(!(newStreet == edit_street )){

                $("#edit_street_check").val('true');

                }else{

                $("#edit_street_check").val('false');
                }

                })

                $("#edit_address").change(function(){

                var newAddress = $(this).val();

                if(!(newAddress == edit_address )){

                $("#edit_address_check").val('true');

                }else{

                $("#edit_address_check").val('false');
                }

                })

                $("#edit_email_address").change(function(){

                var newEmail = $(this).val();

                if(!(newEmail == edit_email_address )){

                $("#edit_email_address_check").val('true');

                }else{

                $("#edit_email_address_check").val('false');
                }

                })

                $("#edit_contact_number").change(function(){

                var newNumber = $(this).val();

                if(!(newNumber == edit_contact_number )){

                $("#edit_contact_number_check").val('true');

                }else{

                $("#edit_contact_number_check").val('false');
                }

                })

                $("#edit_fathers_name").change(function(){

                var newtatay = $(this).val();

                if(!(newtatay == edit_fathers_name )){

                $("#edit_fathers_name_check").val('true');

                }else{

                $("#edit_fathers_name_check").val('false');
                }

                })

                $("#edit_mothers_name").change(function(){

                var newNanay = $(this).val();

                if(!(newNanay == edit_mothers_name )){

                $("#edit_mothers_name_check").val('true');

                }else{

                $("#edit_mothers_name_check").val('false');
                }

                })

                $("#edit_guardian").change(function(){

                var newGuardian = $(this).val();

                if(!(newGuardian == edit_guardian )){

                $("#edit_guardian_check").val('true');

                }else{

                $("#edit_guardian_check").val('false');
                }

                })

                $("#edit_guardian_contact").change(function(){

                var newGcontact = $(this).val();

                if(!(newGcontact == edit_guardian_contact )){

                $("#edit_guardian_contact_check").val('true');

                }else{

                  $("#edit_guardian_contact_check").val('false');
                }

                })






                $.validator.setDefaults({
          submitHandler: function (form) {
            Swal.fire({
              title: '<strong class="text-warning">Are you sure?</strong>',
              html: "<b>You want edit your details?</b>",
              type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Edit it!',
              allowOutsideClick: false,
              width: '400px',
            }).then((result) => {
              if (result.value) {

                var formData = new FormData(form)
                  
                  formData.append("edit_first_name_check",$("#edit_first_name_check").val())
                  formData.append("edit_last_name_check",$("#edit_last_name_check").val())
                  formData.append("edit_voters_check",$("#edit_voters_check").val())
                  formData.append("edit_pwd_check",$("#edit_pwd_check").val())
                  formData.append("edit_birth_date_check",$("#edit_birth_date_check").val())
                  formData.append("edit_birth_place_check",$("#edit_birth_place_check").val())
                  formData.append("edit_middle_name_check",$("#edit_middle_name_check").val())
                  formData.append("edit_suffix_check",$("#edit_suffix_check").val())
                  formData.append("edit_gender_check",$("#edit_gender_check").val())
                  formData.append("edit_civil_status_check",$("#edit_civil_status_check").val())
                  formData.append("edit_religion_check",$("#edit_religion_check").val())
                  formData.append("edit_nationality_check",$("#edit_nationality_check").val())
                  formData.append("edit_municipality_check",$("#edit_municipality_check").val())
                  formData.append("edit_zip_check",$("#edit_zip_check").val())
                  formData.append("edit_barangay_check",$("#edit_barangay_check").val())
                  formData.append("edit_house_number_check",$("#edit_house_number_check").val())
                  formData.append("edit_street_check",$("#edit_street_check").val())
                  formData.append("edit_address_check",$("#edit_address_check").val())
                  formData.append("edit_email_address_check",$("#edit_email_address_check").val())
                  formData.append("edit_contact_number_check",$("#edit_contact_number_check").val())
                  formData.append("edit_fathers_name_check",$("#edit_fathers_name_check").val())
                  formData.append("edit_mothers_name_check",$("#edit_mothers_name_check").val())
                  formData.append("edit_guardian_check",$("#edit_guardian_check").val())
                  formData.append("edit_guardian_contact_check",$("#edit_guardian_contact_check").val())
                  formData.append("edit_pwd_info_check",$("#edit_pwd_info_check").val())
                  formData.append("edit_single_parent_check",$("#edit_single_parent_check").val())
                  

                  $.ajax({
                    url: 'editResidence.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success:function(data){
                      Swal.fire({
                        title: '<strong class="text-success">SUCCESS</strong>',
                        type: 'success',
                        html: '<b>Updated Your Details has Successfully<b>',
                        width: '400px',
                        confirmButtonColor: '#6610f2',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 2000,
                      }).then(()=>{
                        
                          window.location.reload();
                        
                      })
                    }
                }).fail(function(){
                    Swal.fire({
                      title: '<strong class="text-danger">Ooppss..</strong>',
                      type: 'error',
                      html: '<b>Something went wrong with ajax !<b>',
                      width: '400px',
                      confirmButtonColor: '#6610f2',
                    })
                })
              }
            })
            
          }
        });
      $('#editResidenceForm').validate({
        rules: {
          edit_first_name: {
            required: true,
            minlength: 2
          },
          edit_last_name: {
            required: true,
            minlength: 2
          },
          edit_birth_date: {
            required: true,
          },
          edit_address:{
            required: true,
          },
          edit_contact_number:{
            required: true,
            minlength: 11
          },
          edit_guardian_contact:{
            required: true,
            minlength: 11
          },
          edit_email_address:{
            email: true,
          },
        },
        messages: {
          edit_first_name: {
            required: "<span class='text-danger text-bold'>First Name is Required</span>",
            minlength: "<span class='text-danger'>First Name must be at least 2 characters long</span>"
          },
          edit_last_name: {
            required: "<span class='text-danger text-bold'>Last Name is Required</span>",
            minlength: "<span class='text-danger'>Last Name must be at least 2 characters long</span>"
          },

          edit_birth_date: {
            required: "<span class='text-danger text-bold'>Birth Date is Required</span>",
          },
          edit_address: {
            required: "<span class='text-danger text-bold'>Address is Required</span>",
          },
          edit_contact_number: {
            required: "<span class='text-danger text-bold'>Contact Number is Required</span>",
            minlength: "<span class='text-danger'>Input Exact Contact Number</span>"
          },
          edit_guardian_contact: {
            required: "<span class='text-danger text-bold'>Guardian Contact is Required</span>",
            minlength: "<span class='text-danger'>Input Exact Contact Number</span>"
          },
          edit_email_address:{
            email:"<span class='text-danger text-bold'>Enter Valid Email!</span>",
            },
        },
        tooltip_options: {
          '_all_': {
            placement: 'bottom',
            html:true,
          },
          
        },
      });
    })









    $('#display_edit_image_residence_container').on('click',function(){
      $("#edit_image_residence").click();
    })
    $("#edit_image_residence").change(function(){
        editDsiplayImage(this);
      })

    function editDsiplayImage(input){
        if(input.files && input.files[0]){
          var reader = new FileReader();
          var edit_image_residence = $("#edit_image_residence").val().split('.').pop().toLowerCase();

          if(edit_image_residence != ''){
            if(jQuery.inArray(edit_image_residence, ['gif','png','jpeg','jpg']) == -1){
              Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                icon: 'error',
                html: '<b>Invalid Image File</b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              })
              $("#edit_image_residence").val('');
              $("#display_edit_image_residence").attr('src', '<?= $row_resident['image_path'] ?>');
              return false;
            }
          }
            reader.onload = function(e){
              $("#display_edit_image_residence").attr('src', e.target.result);
              $("#display_edit_image_residence").hide();
              $("#display_edit_image_residence").fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
      }
  })
</script>


<script>
// Restricts input for each element in the set of matched elements to the given inputFilter.
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

 
  $("#edit_contact_number, #edit_zip, #edit_guardian_contact, #edit_age").inputFilter(function(value) {
  return /^-?\d*$/.test(value); 
  
  });


  $("#edit_first_name, #edit_middle_name, #edit_last_name, #edit_suffix, #edit_religion, #edit_nationality, #edit_municipality, #edit_fathers_name, #edit_mothers_name, #edit_guardian").inputFilter(function(value) {
  return /^[a-z, ]*$/i.test(value); 
  });
  
  $("#edit_street, #edit_birth_place, #edit_house_number").inputFilter(function(value) {
  return /^[0-9a-z, ,-]*$/i.test(value); 
  });

</script>

</body>
</html>
