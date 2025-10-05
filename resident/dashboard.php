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
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];

    $sql_resident = "SELECT * FROM residence_information WHERE residence_id = '$user_id'";
    $query_resident = $con->query($sql_resident) or die($con->error);
    $row_resident = $query_resident->fetch_assoc();

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
    echo '<script> window.location.href = "../login.php"; </script>';
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
  <title>Barangay Hulo Dashboard</title>

  <link rel="preload" href="../assets/logo/cover.JPG" as="image">
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
      padding: 40px 20px;
    }

    .welcome-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
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

    .welcome-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .welcome-header::before {
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

    .logo-container {
      position: relative;
      z-index: 1;
      margin-bottom: 20px;
    }

    .logo-main {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      border: 6px solid white;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
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

    .welcome-text {
      color: white;
      font-size: 32px;
      font-weight: 300;
      position: relative;
      z-index: 1;
      margin-bottom: 10px;
    }

    .user-name {
      font-weight: 700;
      font-size: 42px;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .services-container {
      padding: 50px 40px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .service-card {
      background: white;
      border-radius: 20px;
      padding: 15px 20px;
      text-align: center;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      cursor: pointer;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 0;
    }

    .service-card:hover::before {
      opacity: 1;
    }

    .service-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 25px 50px rgba(179, 0, 0, 0.3);
    }

    .service-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 30px;
      transition: all 0.4s ease;
      position: relative;
      z-index: 1;
    }

    .service-card:nth-child(1) .service-icon {
      background: linear-gradient(135deg, #b30000 0%, #9C0000FF 100%);
      color: white;
    }

    .service-card:nth-child(2) .service-icon {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
    }

    .service-card:nth-child(3) .service-icon {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      color: white;
    }

    .service-card:hover .service-icon {
      background: white;
      transform: rotate(360deg) scale(1.1);
    }

    .service-card:nth-child(1):hover .service-icon {
      color: #b30000;
    }

    .service-card:nth-child(2):hover .service-icon {
      color: #f5576c;
    }

    .service-card:nth-child(3):hover .service-icon {
      color: #4facfe;
    }

    .service-title {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      margin-bottom: 12px;
      transition: color 0.4s ease;
      position: relative;
      z-index: 1;
    }

    .service-card:hover .service-title {
      color: white;
    }

    .service-description {
      font-size: 14px;
      color: #666;
      line-height: 1.6;
      transition: color 0.4s ease;
      position: relative;
      z-index: 1;
    }

    .service-card:hover .service-description {
      color: rgba(255, 255, 255, 0.9);
    }

    footer.main-footer {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      color: #333;
      text-align: center;
      padding: 20px;
      font-weight: 500;
      border-top: 3px solid #b30000;
      font-size: 14px;
    }

    footer .fas {
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
        padding: 30px 15px;
      }

      .welcome-text {
        font-size: 28px;
      }

      .user-name {
        font-size: 36px;
      }

      .services-container {
        padding: 40px 30px;
        gap: 25px;
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

      .welcome-card {
        border-radius: 20px;
      }

      .welcome-header {
        padding: 30px 20px;
      }

      .logo-main {
        width: 140px;
        height: 140px;
        border: 5px solid white;
      }

      .welcome-text {
        font-size: 24px;
      }

      .user-name {
        font-size: 32px;
        letter-spacing: 1px;
      }

      .services-container {
        grid-template-columns: 1fr;
        padding: 30px 20px;
        gap: 20px;
      }

      .service-card {
        border-radius: 16px;
      }

      .service-icon {
        width: 70px;
        height: 70px;
        font-size: 35px;
      }

      .service-title {
        font-size: 22px;
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

      .welcome-card {
        border-radius: 16px;
      }

      .welcome-header {
        padding: 24px 16px;
      }

      .logo-main {
        width: 120px;
        height: 120px;
        border: 4px solid white;
      }

      .welcome-text {
        font-size: 20px;
        margin-bottom: 8px;
      }

      .user-name {
        font-size: 28px;
        letter-spacing: 1px;
      }

      .services-container {
        padding: 25px 15px;
        gap: 15px;
      }

      .service-card {
        padding: 12px 16px;
        border-radius: 14px;
      }

      .service-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-bottom: 12px;
      }

      .service-title {
        font-size: 20px;
        margin-bottom: 10px;
      }

      .service-description {
        font-size: 13px;
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

      .logo-main {
        width: 100px;
        height: 100px;
        border: 3px solid white;
      }

      .welcome-text {
        font-size: 18px;
      }

      .user-name {
        font-size: 24px;
      }

      .services-container {
        padding: 20px 12px;
      }

      .service-icon {
        width: 55px;
        height: 55px;
        font-size: 28px;
      }

      .service-title {
        font-size: 18px;
      }

      .service-description {
        font-size: 12px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .content-wrapper {
        padding: 20px 15px;
        min-height: auto;
      }

      .welcome-header {
        padding: 20px 16px;
      }

      .logo-main {
        width: 100px;
        height: 100px;
      }

      .welcome-text {
        font-size: 18px;
        margin-bottom: 5px;
      }

      .user-name {
        font-size: 24px;
      }

      .services-container {
        padding: 25px 20px;
        gap: 15px;
      }

      .service-icon {
        width: 50px;
        height: 50px;
        font-size: 25px;
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
      .service-card {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .service-card {
        flex-direction: column;
      }
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <nav class="main-header navbar navbar-expand-md custom-navbar">
      <div class="container">
        <a href="#" class="navbar-brand">
          <img src="../assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="brand-image">
          <span class="brand-text">Barangay Hulo</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link active">
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

    <div class="content-wrapper">
      <div class="content">
        <div class="container">
          <div class="welcome-card">
            <div class="welcome-header">
              <div class="logo-container">
                <img src="../assets/logo/LogoHulo.JPG" alt="Barangay Logo" class="logo-main">
              </div>
              <h2 class="welcome-text">Welcome,</h2>
              <h1 class="user-name"><?= $first_name_user ?></h1>
            </div>

            <div class="services-container">
              <a href="myInfo.php" style="text-decoration: none;">
                <div class="service-card">
                  <div class="service-icon">
                    <i class="fas fa-user"></i>
                  </div>
                  <h3 class="service-title">My Information</h3>
                  <p class="service-description">View and manage your personal information and residence details</p>
                </div>
              </a>

              <a href="certificate.php" style="text-decoration: none;">
                <div class="service-card">
                  <div class="service-icon">
                    <i class="fas fa-certificate"></i>
                  </div>
                  <h3 class="service-title">Certificates</h3>
                  <p class="service-description">Request and line-up barangay certificates and clearances</p>
                </div>
              </a>

              <a href="myRecord.php" style="text-decoration: none;">
                <div class="service-card">
                  <div class="service-icon">
                    <i class="fas fa-book-open"></i>
                  </div>
                  <h3 class="service-title">Blotter Records</h3>
                  <p class="service-description">Access your blotter records and incident reports</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="main-footer">
      <i class="fas fa-map-marker-alt"></i> 91 Coronado, Barangay Hulo, Mandaluyong, Philippines
    </footer>

  </div>

  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
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

</body>

</html>