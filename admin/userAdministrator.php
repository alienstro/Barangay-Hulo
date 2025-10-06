<?php

include_once '../connection.php';
session_start();

try {



  if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {

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
  <title></title>


  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
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
      font-family: 'Poppins', sans-serif !important;
      background: white;
      min-height: 100vh;
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

    .card {
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

    .card-body {
      background: white;
      padding: 30px;
    }

    .main-header {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
      border-bottom: 3px solid #b30000;
    }

    .main-header .navbar-nav .nav-link {
      color: #b30000 !important;
      font-weight: 500;
    }

    .main-sidebar {
      background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%) !important;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
    }

    .sidebar-dark-primary .nav-link {
      color: #c2c7d0 !important;
      transition: all 0.3s ease;
      border-radius: 8px;
      margin: 4px 8px;
    }

    .sidebar-dark-primary .nav-link:hover {
      background: rgba(179, 0, 0, 0.2) !important;
      color: white !important;
    }

    .sidebar-dark-primary .nav-link.active {
      background: #b30000 !important;
      color: white !important;
    }

    .bg-indigo {
      background: #b30000 !important;
    }

    .text-red {
      color: #b30000 !important;
    }

    .brand-link {
      border-bottom: 2px solid #b30000;
      padding: 20px;
    }

    .img-bordered-sm {
      border: 4px solid #b30000 !important;
    }

    .preloader {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%) !important;
    }

    .table-responsive {
      border-radius: 12px;
      overflow: hidden;
    }

    .table thead {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%) !important;
    }

    .table thead th {
      color: white !important;
      font-weight: 600;
      border: none;
      padding: 15px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(179, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(179, 0, 0, 0.1);
    }

    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 8px 16px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
      border-color: #b30000;
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    .dataTables_wrapper .dataTables_filter label {
      color: #b30000;
      font-weight: 600;
    }

    .dataTables_wrapper .dataTables_paginate .page-link {
      border: none;
    }

    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      color: #b30000;
      border-color: transparent;
      background: white;
      border-radius: 8px;
      margin: 0 4px;
      transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
      color: #fff;
      border: transparent;
      background: #b30000 !important;
      font-weight: bold;
      box-shadow: 0 2px 8px rgba(179, 0, 0, 0.3);
    }

    .page-link:focus {
      border-color: #b30000;
      outline: 0;
      -webkit-box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    .dataTables_length select {
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
      color: #333;
      padding: 4px 8px;
      background: white;
    }

    .dataTables_length span {
      color: #b30000;
      font-weight: 600;
    }

    .dataTables_info {
      font-size: 13px;
      margin-top: 8px;
      font-weight: 500;
      color: #b30000;
    }

    .dataTables_scrollHeadInner,
    .table {
      table-layout: auto;
      width: 100% !important;
    }

    fieldset {
      border: 3px solid #b30000 !important;
      padding: 0 1.4em 1.4em 1.4em !important;
      margin: 0 0 1.5em 0 !important;
      -webkit-box-shadow: 0px 0px 0px 0px #b30000;
      box-shadow: 0px 0px 0px 0px #b30000;
    }

    legend {
      font-size: 1.2em !important;
      font-weight: bold !important;
      color: #b30000;
      text-align: left !important;
      width: auto;
      padding: 0 10px;
      border-bottom: none;
    }

    #display_image {
      height: 120px;
      width: auto;
      max-width: 500px;
      border: 3px solid #b30000;
      border-radius: 12px;
    }

    /* Form Styles */
    .form-control:focus {
      border-color: #b30000;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    .btn-primary {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #8b0000 0%, #6a0000 100%);
    }

    /* Responsive Design */
    @media (max-width: 991px) {
      .content-wrapper {
        padding: 30px 15px;
      }

      .card-body {
        padding: 24px;
      }
    }

    @media (max-width: 767px) {
      .content-wrapper {
        padding: 20px 10px;
        background-attachment: scroll;
      }

      .card {
        border-radius: 20px;
      }

      .card-body {
        padding: 20px;
      }
    }

    @media (max-width: 480px) {
      .card {
        border-radius: 16px;
      }

      .card-body {
        padding: 16px;
      }

      #display_image {
        max-width: 100%;
      }
    }
  </style>


</head>

<body class="hold-transition sidebar-mini sidebar-collapse  layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble " src="../assets/dist/img/loader.gif" alt="AdminLTELogo" height="70" width="70">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <h5><a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></h5>
        </li>
        <li class="nav-item d-none d-sm-inline-block" style="font-variant: small-caps;">
          <h5 class="nav-link text-white"><?= $barangay ?></h5>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <h5 class="nav-link text-white">-</h5>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <h5 class="nav-link text-white"><?= $zone ?></h5>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <h5 class="nav-link text-white">-</h5>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <h5 class="nav-link text-white"><?= $district ?></h5>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="myProfile.php" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <?php
                if ($user_image != '' || $user_image != null || !empty($user_image)) {
                  echo '<img src="../assets/dist/img/' . $user_image . '" class="img-size-50 mr-3 img-circle alt="User Image">';
                } else {
                  echo '<img src="../assets/dist/img/image.png" class="img-size-50 mr-3 img-circle alt="User Image">';
                }
                ?>

                <div class="media-body">
                  <h3 class="dropdown-item-title py-3">
                    <?= ucfirst($first_name_user) . ' ' . ucfirst($last_name_user) ?>
                  </h3>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="../logout.php" class="dropdown-item dropdown-footer">LOGOUT</a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
      <!-- Brand Logo -->
      <div class="brand-link text-center">
        <?php
        if ($image != '' || $image != null || !empty($image)) {
          echo '<img src="' . $image_path . '" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="Barangay Logo" style="width: 150px; height: 150px;">';
        } else {
          echo '<img src="../assets/logo/blank.png" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="Barangay Logo" style="width: 150px; height: 150px;">';
        }
        ?>
        <span class="brand-text font-weight-light"></span>
      </div>

      <!-- Sidebar -->
      <div class="sidebar">


        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
          <div class="image">
            <?php
            if (!empty($user_image)) {
              $imgSrc = (strpos($user_image, '/') !== false) ? $user_image : '../assets/dist/img/' . $user_image;
              echo '<img src="' . $imgSrc . '" class="img-circle elevation-2" alt="User Image">';
            } else {
              echo '<img src="../assets/dist/img/image.png" class="img-circle elevation-2" alt="User Image">';
            }
            ?>
          </div>
          <div class="info text-center">
            <a href="#" class="d-block text-bold"><?= strtoupper($user_type) ?></a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link ">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                  Barangay Official
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="newOfficial.php" class="nav-link ">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>New Official</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="allOfficial.php" class="nav-link">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>List of Official</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="officialEndTerm.php" class="nav-link ">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>Official End Term</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Residence
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="newResidence.php" class="nav-link ">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>New Residence</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="allResidence.php" class="nav-link">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>All Residence</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="archiveResidence.php" class="nav-link ">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>Archive Residence</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item ">
              <a href="requestCertificate.php" class="nav-link">
                <i class="nav-icon fas fa-certificate"></i>
                <p>
                  Certificate
                </p>
              </a>
            </li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link bg-indigo">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Users
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="usersResident.php" class="nav-link ">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>Resident</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="userAdministrator.php" class="nav-link active">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>Administrator</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="position.php" class="nav-link">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>
                  Position
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="blotterRecord.php" class="nav-link">
                <i class="nav-icon fas fa-clipboard"></i>
                <p>
                  Blotter Record
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="report.php" class="nav-link">
                <i class="nav-icon fas fa-bookmark"></i>
                <p>
                  Reports
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings.php" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                  Settings
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="systemLog.php" class="nav-link">
                <i class="nav-icon fas fa-history"></i>
                <p>
                  System Logs
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="backupRestore.php" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  Backup/Restore
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


      <!-- Main content -->
      <section class="content mt-3">
        <div class="container-fluid">

          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <button type="button" id="openModal" data-toggle="modal" data-target="#newAdministratorModal" class="btn bg-black btn-flat elevation-5 px-3"><i class="fas fa-user-plus"></i> NEW ADMINISTRATOR </button>
              </div>
            </div>
            <div class="card-body">
              <fieldset>
                <legend>NUMBER OF USERS ADMINISTRATOR <span id="total"></span></legend>


                <table class="table table-striped table-hover " id="userTableAdministrator">
                  <thead class="bg-black">
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                </table>
              </fieldset>
            </div>
          </div>


        </div><!--/. container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; <?php echo date("Y"); ?> - <?php echo date('Y', strtotime('+1 year'));  ?> </strong>

      <div class="float-right d-none d-sm-inline-block">
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->





  <!-- Modal -->
  <div class="modal fade" id="newAdministratorModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <form id="addUserAdministratorForm" method="post" enctype="multipart/form-data" autocomplete="off">

          <div class="modal-header">
            <h5 class="modal-title">Administrator</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-12 text-center">
                  <img src="../assets/dist/img/image.png" style="cursor: pointer;" class="img-circle " alt="adminImage" id="display_image">
                  <input type="file" id="image" name="image" style="display: none;">
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" id="password" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" name="email" id="email" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" maxlength="11" id="contact_number" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary elevation-5 px-3 btn-flat" data-dismiss="modal"><i class="fas fa-times  "></i> CLOSE</button>
            <button type="submit" class="btn btn-success elevation-5 px-3 btn-flat"><i class="fas fa-plus"></i> ADD</button>
          </div>

        </form>

      </div>
    </div>
  </div>




  <div id="imagemodal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color: #000">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <img src="" class="imagepreview img-circle" style="width: 100%;">
        </div>
      </div>
    </div>
  </div>


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
  <script src="../assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <script src="../assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <div id="displayUserAdministrator"></div>
  <script>
    $(document).ready(function() {

      userTableAdministrator()




      $("#openModal").on('click', function() {
        $("#addUserAdministratorForm")[0].reset();
        $("#display_image").attr('src', '../assets/dist/img/image.png');
      })

      $('#display_image').on('click', function() {
        $("#image").click();
      })
      $("#image").change(function() {
        editDsiplayImage(this);
      })


      $(function() {
        $.validator.setDefaults({
          submitHandler: function(form) {
            Swal.fire({
              title: '<strong class="text-warning">Are you sure?</strong>',
              html: "<b>You want add this user?</b>",
              type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#b30000',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, add it!',
              allowOutsideClick: false,
              width: '400px',
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url: 'addAdministrator.php',
                  type: 'POST',
                  data: new FormData(form),
                  processData: false,
                  contentType: false,
                  cache: false,
                  dataType: 'json',
                  success: function(resp) {
                    if (!resp || typeof resp !== 'object') {
                      Swal.fire({
                        title: '<strong class="text-danger">Ooppss..</strong>',
                        type: 'error',
                        html: '<b>Invalid server response.<b>',
                        width: '400px',
                        confirmButtonColor: '#b30000',
                      });
                      return;
                    }

                    if (resp.status === 'error') {
                      var msg = resp.message || 'Validation error';
                      Swal.fire({
                        title: '<strong class="text-danger">ERROR</strong>',
                        type: 'error',
                        html: '<b>' + msg + '<b>',
                        width: '520px',
                        confirmButtonColor: '#b30000',
                      });
                      return;
                    }

                    // success
                    if (resp.verify === true) {
                      Swal.fire({
                        title: '<strong class="text-warning">SUCCESS - Verify Email</strong>',
                        type: 'warning',
                        html: '<b>Administrator created. An OTP was sent to the provided email. The administrator must verify their email before logging in.<br><br>Please inform them to check their inbox.<b>',
                        width: '520px',
                        confirmButtonColor: '#b30000',
                        allowOutsideClick: false,
                      }).then(() => {
                        $("#userTableAdministrator").DataTable().ajax.reload();
                        $("#addUserAdministratorForm")[0].reset();
                        $("#display_image").attr('src', '../assets/dist/img/image.png');
                        $("#newAdministratorModal").modal('hide');
                      })
                    } else {
                      Swal.fire({
                        title: '<strong class="text-success">SUCCESS</strong>',
                        type: 'success',
                        html: '<b>Added Administrator successfully.<b>',
                        width: '400px',
                        confirmButtonColor: '#b30000',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 2000,
                      }).then(() => {
                        $("#userTableAdministrator").DataTable().ajax.reload();
                        $("#addUserAdministratorForm")[0].reset();
                        $("#display_image").attr('src', '../assets/dist/img/image.png');
                        $("#newAdministratorModal").modal('hide');
                      })
                    }

                  }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                  Swal.fire({
                    title: '<strong class="text-danger">Ooppss..</strong>',
                    type: 'error',
                    html: '<b>Something went wrong with ajax: ' + (errorThrown || textStatus) + ' <b>',
                    width: '520px',
                    confirmButtonColor: '#b30000',
                  })
                })
              }
            })

          }
        });
        $('#addUserAdministratorForm').validate({
          rules: {
            first_name: {
              required: true,
              minlength: 2
            },
            last_name: {
              required: true,
              minlength: 2
            },
            username: {
              required: true,
              minlength: 6
            },
            password: {
              required: true,
              minlength: 6
            },
            contact_number: {
              required: true,
              minlength: 11,
              digits: true
            },

            email: {
              email: true
            },
          },
          messages: {
            first_name: {
              required: "<span class='text-danger text-bold'>First Name is Required</span>",
              minlength: "<span class='text-danger'>First Name must be at least 2 characters long</span>"
            },
            last_name: {
              required: "<span class='text-danger text-bold'>Last Name is Required</span>",
              minlength: "<span class='text-danger'>Last Name must be at least 2 characters long</span>"
            },
            username: {
              required: "<span class='text-danger text-bold'>Username is Required</span>",
              minlength: "<span class='text-danger'>Username must be at least 6 characters long</span>"
            },
            password: {
              required: "<span class='text-danger text-bold'>Password is Required</span>",
              minlength: "<span class='text-danger'>Password must be at least 6 characters long</span>",
              digits: "<span class='text-danger'>Only numbers allowed</span>"
            },
            contact_number: {
              email: {
                email: "<span class='text-danger'>Enter Valid Email!</span>"
              },
              required: "This field is required",
              digits: "Only numbers allowed",
              minlength: "Enter exact contact number (e.g 09xxxxxxxxx)"
            },

          },
          tooltip_options: {
            '_all_': {
              placement: 'bottom',
              html: true,
            },

          },
        });
      })


      function editDsiplayImage(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          var image = $("#image").val().split('.').pop().toLowerCase();

          if (image != '') {
            if (jQuery.inArray(image, ['gif', 'png', 'jpeg', 'jpg']) == -1) {
              Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                type: 'error',
                html: '<b>Invalid Image File<b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              })
              $("#image").val('');
              $("#display_image").attr('src', '../assets/dist/img/image.png');
              return false;
            }
          }
          reader.onload = function(e) {
            $("#display_image").attr('src', e.target.result);
            $("#display_image").hide();
            $("#display_image").fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }




      $(document).on('click', '.viewUserAdministrator', function() {
        var user_id = $(this).attr('id');

        $("#displayUserAdministrator").html('');

        $.ajax({
          url: 'viewUserAdministrator.php',
          type: 'POST',
          data: {
            user_id: user_id
          },
          cache: false,
          success: function(data) {
            $("#displayUserAdministrator").html(data);
            $("#editUserAdministratorModal").modal('show');
          }
        }).fail(function() {
          Swal.fire({
            title: '<strong class="text-danger">Ooppss..</strong>',
            type: 'error',
            html: '<b>Something went wrong with ajax !<b>',
            width: '400px',
            confirmButtonColor: '#b30000',
          })
        })

      })






      function userTableAdministrator() {
        var userTableAdministrator = $("#userTableAdministrator").DataTable({

          processing: true,
          serverSide: true,
          autoWith: false,
          responsive: true,
          ajax: {
            url: 'userTableAdministrator.php',
            type: 'POST',
          },
          order: [],
          columnDefs: [{
              orderable: false,
              targets: 0,
            },
            {
              orderable: false,
              targets: 4,
            },
            {

              targets: 4,
              className: 'text-center',
            },
          ],
          drawCallback: function(data) {
            $('#total').text(data.json.total);
          }
        })

      }
      $(document).on('click', '.pop', function() {
        $('.imagepreview').attr('src', $(this).find('img').attr('src'));
        $('#imagemodal').modal('show');
      });


      $(document).on('click', '.deleteUserAdministrator', function() {
        var user_id = $(this).attr('id');
        Swal.fire({
          title: '<strong class="text-danger">ARE YOU SURE?</strong>',
          html: "<b>You want delete this User?</b>",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#b30000',
          cancelButtonColor: '#d33',
          allowOutsideClick: false,
          confirmButtonText: 'Yes, delete it!',
          width: '400px',
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: 'deleteUserAdministrator.php',
              type: 'POST',
              data: {
                user_id: user_id,
              },
              cache: false,
              success: function(data) {
                Swal.fire({
                  title: '<strong class="text-success">Success</strong>',
                  type: 'success',
                  html: '<b>Deleted User has Successfully<b>',
                  width: '400px',
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(() => {
                  $("#userTableAdministrator").DataTable().ajax.reload();
                })
              }
            }).fail(function() {
              Swal.fire({
                title: '<strong class="text-danger">Ooppss..</strong>',
                type: 'error',
                html: '<b>Something went wrong with ajax !<b>',
                width: '400px',
                confirmButtonColor: '#b30000',
              })
            })
          }
        })

      })

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


    $("#contact_number").inputFilter(function(value) {
      return /^-?\d*$/.test(value);

    });


    // Names: letters, spaces and comma only
    $("#first_name,#middle_name,#last_name").inputFilter(function(value) {
      return /^[a-z, ]*$/i.test(value);
    });

    // Username: allow alphanumeric and common username chars (no spaces)
    $("#username").inputFilter(function(value) {
      return /^[A-Za-z0-9._-]*$/.test(value);
    });

    $("#password").inputFilter(function(value) {
      return /^[0-9a-z, ,-]*$/i.test(value);
    });
  </script>


</body>

</html>
