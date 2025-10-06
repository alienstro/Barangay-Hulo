<?php
// index.php
include_once 'connection.php';
session_start();

// --- Redirect if user is already logged in ---
if (isset($_SESSION['user_id']) && $_SESSION['user_type']) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $query = $con->query($sql) or die($con->error);
  $row = $query->fetch_assoc();
  $account_type = $row['user_type'];

  if ($account_type == 'admin') {
    echo '<script>window.location.href="admin/dashboard.php";</script>';
    exit;
  } elseif ($account_type == 'secretary') {
    echo '<script>window.location.href="secretary/dashboard.php";</script>';
    exit;
  } else {
    echo '<script>window.location.href="resident/dashboard.php";</script>';
    exit;
  }
}

// --- Barangay Info ---
// Default values 
$barangay = "Hulo";
$zone = "Zone 4";
$district = "Mandaluyong";
$image = "default_logo.png";
$postal_address = "Barangay Hulo, Mandaluyong City";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - Welcome</title>

  <link rel="preload" href="assets/logo/cover.JPG" as="image">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
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

    /* Hero Cover Section */
    .hero-cover {
      position: relative;
      width: 100%;
      height: 100vh;
      min-height: 600px;
      margin-top: 0;
      overflow: hidden;
    }

    .hero-cover-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }

    .hero-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(179, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.7) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero-content {
      text-align: center;
      color: white;
      z-index: 10;
      padding: 20px;
      animation: fadeInUp 1s ease;
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

    .hero-logo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 5px solid white;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
      object-fit: cover;
      margin-bottom: 30px;
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

    .hero-title {
      font-size: 60px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 3px;
      margin-bottom: 15px;
      text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.5);
    }

    .hero-subtitle {
      font-size: 24px;
      font-weight: 300;
      margin-bottom: 40px;
      opacity: 0.95;
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-hero {
      padding: 15px 40px;
      font-size: 18px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary-hero {
      background: white;
      color: #b30000;
      box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
    }

    .btn-primary-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(255, 255, 255, 0.5);
      background: #f8f9fa;
      color: #b30000;
      text-decoration: none;
    }

    .btn-outline-hero {
      background: transparent;
      color: white;
      border: 3px solid white;
    }

    .btn-outline-hero:hover {
      background: white;
      color: #b30000;
      transform: translateY(-3px);
      text-decoration: none;
    }

    /* Officials Section */
    .officials-section {
      display: flex;
      flex-direction: column;
      padding: 80px 0;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      gap: 70px;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 42px;
      font-weight: 700;
      color: #b30000;
      margin-bottom: 15px;
      position: relative;
      display: inline-block;
    }

    .section-title h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, #b30000, #ff6b6b);
      border-radius: 2px;
    }

    .section-title p {
      font-size: 18px;
      color: #666;
      margin-top: 25px;
    }

    .officials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, 320px);
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      justify-content: center;
    }

    .officials-grid-leader {
      display: grid;
      grid-template-columns: 320px;
      gap: 40px;
      margin: 0 auto;
      padding: 0 20px;
      justify-content: center;
    }


    .officials-gap {
      display: flex;
      flex-direction: column;
      gap: 40px;
      width: 100%;
    }

    .official-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
    }

    .official-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, #b30000, #ff6b6b);
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }

    .official-card:hover::before {
      transform: scaleX(1);
    }

    .official-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 50px rgba(179, 0, 0, 0.2);
    }

    .official-image-wrapper {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      height: 350px;
    }

    .official-image {
      width: 100%;
      object-fit: cover;
      object-position: center;
      transition: transform 0.4s ease;
    }

    .official-card:hover .official-image {
      transform: scale(1.1);
    }

    .official-info {
      padding: 25px;
      text-align: center;
    }

    .official-position {
      font-size: 14px;
      font-weight: 600;
      color: #b30000;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .official-name {
      font-size: 20px;
      font-weight: 700;
      color: #333;
      margin: 0;
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

      .hero-title {
        font-size: 48px;
      }

      .hero-subtitle {
        font-size: 20px;
      }

      .section-title h2 {
        font-size: 36px;
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

      .hero-cover {
        min-height: 100vh;
      }

      .hero-content {
        padding: 20px 15px;
      }

      .hero-title {
        font-size: 36px;
        letter-spacing: 2px;
      }

      .hero-subtitle {
        font-size: 18px;
        margin-bottom: 30px;
      }

      .hero-logo {
        width: 120px;
        height: 120px;
        margin-bottom: 25px;
      }

      .hero-buttons {
        flex-direction: column;
        width: 100%;
        gap: 15px;
      }

      .btn-hero {
        width: 100%;
        padding: 14px 30px;
        font-size: 16px;
      }

      .section-title h2 {
        font-size: 32px;
      }

      .section-title p {
        font-size: 16px;
      }

      .officials-section {
        padding: 60px 0;
        gap: 50px;
      }

      .officials-grid {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 0 15px;
      }

      .officials-grid-leader {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 0 15px;
      }

      .officials-gap {
        gap: 30px;
      }

      .official-image-wrapper {
        height: 400px;
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

      .hero-cover {
        min-height: 100vh;
      }

      .hero-title {
        font-size: 28px;
        letter-spacing: 1px;
      }

      .hero-subtitle {
        font-size: 16px;
        margin-bottom: 25px;
      }

      .hero-logo {
        width: 100px;
        height: 100px;
        border: 4px solid white;
      }

      .btn-hero {
        padding: 12px 24px;
        font-size: 15px;
      }

      .section-title h2 {
        font-size: 28px;
      }

      .section-title p {
        font-size: 15px;
      }

      .officials-section {
        padding: 50px 0;
      }

      .official-card {
        border-radius: 16px;
      }

      .official-image-wrapper {
        height: 350px;
      }

      .official-info {
        padding: 20px;
      }

      .official-position {
        font-size: 13px;
      }

      .official-name {
        font-size: 18px;
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

      .hero-title {
        font-size: 24px;
      }

      .hero-subtitle {
        font-size: 14px;
      }

      .hero-logo {
        width: 90px;
        height: 90px;
        border: 3px solid white;
      }

      .btn-hero {
        padding: 11px 20px;
        font-size: 14px;
      }

      .section-title h2 {
        font-size: 24px;
      }

      .official-image-wrapper {
        height: 300px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .hero-cover {
        min-height: 100vh;
      }

      .hero-logo {
        width: 80px;
        height: 80px;
      }

      .hero-title {
        font-size: 24px;
      }

      .hero-subtitle {
        font-size: 14px;
        margin-bottom: 20px;
      }

      .hero-buttons {
        gap: 10px;
      }

      .btn-hero {
        padding: 10px 24px;
        font-size: 14px;
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
      .btn-hero {
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
              <a href="index.php" class="nav-link active">
                <i class="fas fa-home"></i> Home
              </a>
            </li>
            <li class="nav-item">
              <a href="register.php" class="nav-link">
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

    <!-- Hero Cover Section -->
    <section class="hero-cover">
      <img src="assets/logo/cover.jpg" alt="Barangay Hulo Cover" class="hero-cover-image">
      <div class="hero-overlay">
        <div class="hero-content">
          <img src="assets/logo/LogoHulo.PNG" alt="Barangay Logo" class="hero-logo">
          <h1 class="hero-title">Barangay Hulo</h1>
          <p class="hero-subtitle">Mandaluyong City • Serving Our Community</p>
          <div class="hero-buttons">
            <a href="register.php" class="btn-hero btn-primary-hero">
              <i class="fas fa-user-plus"></i> Register Now
            </a>
            <a href="login.php" class="btn-hero btn-outline-hero">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Barangay Officials Section -->
    <section class="officials-section">
      <div class="container">
        <div class="section-title">
          <h2 style="padding-bottom: 10px;">Barangay Officials</h2>
          <p>Meet our dedicated leaders serving Barangay Hulo</p>
        </div>

        <!-- Punong baranggay -->
        <div class="officials-gap">
          <div class="officials-grid-leader">
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Joseph Parong Jose.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Punong Barangay</div>
                <h3 class="official-name">JOSEPH PARONG JOSE</h3>
              </div>
            </div>
          </div>
          <div class="officials-grid">
            <!--Brgy. Kagawad -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Samson Sta Teresa Antiojo Jr.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">SAMSON STA. TERESA ANTIOJO JR</h3>
              </div>
            </div>

            <!-- Brgy. Kagawad 2 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Carlo De Leon De Guzman.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">CARLO DE LEON DE GUZMAN</h3>
              </div>
            </div>

            <!--Brgy. Kagawad 3 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Donny Ref Castillo Gandoza.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">DONNY REF CASTILLO GANDOZA</h3>
              </div>
            </div>

            <!-- Brgy. Kagawad 4 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Richard Sierra Santdas.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">RICHARD SIERRRA SANTDAS</h3>
              </div>
            </div>

            <!-- Brgy. Kagawad 5 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Edna Mayores Gorobao.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">EDNA MAYORES GOROBAO</h3>
              </div>
            </div>

            <!-- Brgy. Kagawad 6 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Nikki Santos Amistoso.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">NIKKI SANTOS AMISTOSO</h3>
              </div>
            </div>

            <!-- Brgy. Kagawad 7 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Alex Laura Ignacio.jpg" alt="assets/logo/blank-profile.png" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">Brgy. Kagawad</div>
                <h3 class="official-name">ALEX LAURA IGNACIO</h3>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Sangguniang Kabataan Officials Section -->
      <div class="container">
        <div class="section-title">
          <h2>Sangguniang Kabataan Officials</h2>
          <p>Meet our dedicated youth leaders serving Barangay Hulo</p>
        </div>

        <!-- SK Chairperson -->
        <div class="officials-gap">
          <div class="officials-grid-leader">
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/SK Chairwoman Jermaine BLU Perey.JPG" alt="JERMAINE CLARIZ S. PEREY" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Chairperson</div>
                <h3 class="official-name">JERMAINE CLARIZ S. PEREY</h3>
              </div>
            </div>
          </div>
          <div class="officials-grid">
            <!-- SK Kagawad 1 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Manlosa, Rhuby Rose.jpg" alt="RHUBY ROSE O. MANLOSA" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">RHUBY ROSE O. MANLOSA</h3>
              </div>
            </div>

            <!-- SK Kagawad 2 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Ebro, Eloisa Elize.jpg" alt="ELOISA ELIZE E. EBRO" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">ELOISA ELIZE E. EBRO</h3>
              </div>
            </div>

            <!-- SK Kagawad 3 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Crisostomo, Edrian.jpg" alt="EDRIAN D. CRISOSTOMO" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">EDRIAN D. CRISOSTOMO</h3>
              </div>
            </div>

            <!-- SK Kagawad 4 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Perey, James Alfred.jpg" alt="JAMES ALFRED P. JOSE" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">JAMES ALFRED P. JOSE</h3>
              </div>
            </div>

            <!-- SK Kagawad 5 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Advincula, Mary Ann.jpg" alt="MARY ANN B. ADVINCULA" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">MARY ANN B. ADVINCULA</h3>
              </div>
            </div>

            <!-- SK Kagawad 6 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Victorino, Ronaldo Jr.jpg" alt="RONALDO E. VICTORINO" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">RONALDO E. VICTORINO</h3>
              </div>
            </div>

            <!-- SK Kagawad 7 -->
            <div class="official-card">
              <div class="official-image-wrapper">
                <img src="assets/logo/Pamat, Rency.jpg" alt="RENCY S. PAMAT" class="official-image" onerror="this.onerror=null;this.src='assets/logo/blank-profile.png'">
              </div>
              <div class="official-info">
                <div class="official-position">SK Kagawad</div>
                <h3 class="official-name">RENCY S. PAMAT</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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

  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/dist/js/adminlte.js"></script>
</body>

</html>