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
      height: 100%;
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

    /* Footer - Copied from register.php */
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

    /* Responsive Design */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 36px;
      }

      .hero-subtitle {
        font-size: 18px;
      }

      .hero-logo {
        width: 120px;
        height: 120px;
      }

      .hero-buttons {
        flex-direction: column;
        width: 100%;
      }

      .btn-hero {
        width: 90%;
        padding: 12px 30px;
        font-size: 16px;
      }

      .section-title h2 {
        font-size: 32px;
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

      .official-image-wrapper {
        height: 400px;
      }
    }

    @media (max-width: 480px) {
      .hero-cover {
        min-height: 500px;
      }

      .hero-title {
        font-size: 28px;
        letter-spacing: 1px;
      }

      .hero-subtitle {
        font-size: 16px;
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
          <p class="hero-subtitle">Mandaluyong City • Zone 4 • Serving Our Community</p>
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
      <i class="fas fa-map-marker-alt"></i> 91 Coronado, Barangay Hulo, Mandaluyong, Philippines
    </footer>
  </div>

  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/dist/js/adminlte.js"></script>
</body>

</html>