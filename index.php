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
$sql = "SELECT * FROM `barangay_information` LIMIT 1";
$query = $con->prepare($sql) or die($con->error);
$query->execute();
$result = $query->get_result();
if ($row = $result->fetch_assoc()) {
    $barangay = $row['barangay'];
    $zone = $row['zone'];
    $district = $row['district'];
    $image = $row['image'];
    $image_path = $row['image_path'];
    $id = $row['id'];
    $postal_address = $row['postal_address'];
} else {
    // Default values kung walang laman ang DB
    $barangay = "Hulo";
    $zone = "Zone 4";
    $district = "Mandaluyong";
    $image = "default_logo.png";
    $postal_address = "Barangay Hulo, Mandaluyong City";
}

// --- Carousel Functions ---
function make_query($con)
{
    $sql = "SELECT * FROM carousel";
    $query = $con->query($sql) or die($con->error);
    return $query;
}

function make_slide_indicators($con)
{
    $output = '';
    $count = 0;
    $result = make_query($con);
    while ($row = $result->fetch_assoc()) {
        $active = $count == 0 ? 'class="active"' : '';
        $output .= '<li data-target="#carouselExampleIndicators" data-slide-to="' . $count . '" ' . $active . '></li>';
        $count++;
    }
    return $output;
}

function make_slides($con)
{
    $output = '';
    $count = 0;
    $result = make_query($con);
    while ($row = mysqli_fetch_array($result)) {
        $active = $count == 0 ? 'active' : '';
        $output .= '
        <div class="carousel-item ' . $active . '">
            <img class="d-block w-100" src="' . $row["banner_image_path"] . '" alt="' . $row["banner_title"] . '" />
            <div class="carousel-caption">
                <h3>' . $row["banner_title"] . '</h3>
            </div>
        </div>';
        $count++;
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Barangay Hulo</title>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

<style>
  .rightBar:hover { border-bottom: 3px solid #ff0000; }
  #barangay_logo, .logo {
      height: 150px;
      width: auto;
      max-width: 500px;
  }
  .content-wrapper {
      background-image: url('assets/logo/cover.JPG');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      width: 100%;
      height: auto;
      animation-name: fadeIn;
      animation-duration: 5s;
  }
  @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
  }
</style>
</head>

<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md" style="background-color: #c40000;">
    <div class="container">
      <a href="" class="navbar-brand">
        <img src="assets/dist/img/<?= htmlspecialchars($image) ?>" alt="logo" class="brand-image img-circle">
        <span class="brand-text text-white" style="font-weight: 700">BARANGAY HULO</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse"></div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item">
          <a href="#" class="nav-link text-white rightBar" style="border-bottom: 3px solid #ff0000;">HOME</a>
        </li>
        <li class="nav-item">
          <a href="register.php" class="nav-link text-white rightBar"><i class="fas fa-user-plus"></i> REGISTER</a>
        </li>
        <li class="nav-item">
          <a href="login.php" class="nav-link text-white rightBar"><i class="fas fa-user-alt"></i> LOGIN</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid pt-5">
        <div class="card" style="background-color: rgba(196,0,0,.75);">
          <div class="card-body text-center text-white">
            <h1 class="card-text" style="font-weight: 1000; text-transform: uppercase;">WELCOME BARANGAY HULO</h1>
            <br><br>
            <a href="register.php" class="btn bg-red btn-lg px-3" style="font-weight: 900">REGISTER NOW</a>
            <a href="login.php" class="btn btn-outline-light btn-lg px-3 text-white" style="font-weight: 900; border: 2px solid #fff">LOGIN</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="main-footer text-white text-center py-3" style="background-color: #c40000;">
    <i class="fas fa-map-marker-alt"></i> 91 Coronado, Barangay Hulo, Mandaluyong, Philippines
  </footer>
</div>

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.js"></script>
</body>
</html>
