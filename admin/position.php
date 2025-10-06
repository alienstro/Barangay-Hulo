<?php

include_once '../connection.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {

  $user_id = $_SESSION['user_id'];
  $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
  $stmt_user = $con->prepare($sql_user) or die($con->error);
  $stmt_user->bind_param('s', $user_id);
  $stmt_user->execute();
  $result_user = $stmt_user->get_result();
  $row_user = $result_user->fetch_assoc();
  $first_name_user = $row_user['first_name'] ?? '';
  $last_name_user = $row_user['last_name'] ?? '';
  $user_type = $row_user['user_type'] ?? '';
  $user_image = $row_user['image'] ?? '';
} else {
  echo '<script>
        window.location.href = "../login.php";
      </script>';
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangay Hulo - Position</title>


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

    /* Responsive Design */
    @media (max-width: 991px) {
      .content-wrapper {
        padding: 30px 15px;
      }

      .card-body {
        padding: 24px;
      }

      .table thead th {
        padding: 12px 8px;
        font-size: 14px;
      }

      .table tbody td {
        padding: 10px 8px;
        font-size: 13px;
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

      .table-responsive {
        font-size: 12px;
      }

      .table thead th,
      .table tbody td {
        padding: 8px 6px;
        font-size: 12px;
      }

      .btn {
        padding: 6px 12px;
        font-size: 12px;
      }

      .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        max-width: 200px;
      }

      .dataTables_length,
      .dataTables_info {
        font-size: 12px;
      }

      .modal-dialog {
        margin: 10px;
      }
    }

    @media (max-width: 480px) {
      .card {
        border-radius: 16px;
      }

      .card-body {
        padding: 16px;
      }

      .card-header h3 {
        font-size: 18px;
      }

      .table thead th,
      .table tbody td {
        padding: 6px 4px;
        font-size: 11px;
      }

      .btn {
        padding: 5px 10px;
        font-size: 11px;
      }

      .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        font-size: 12px;
      }

      .form-control {
        font-size: 14px;
      }

      .modal-body {
        padding: 15px;
      }
    }
  </style>


</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
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
              <a href="dashboard.php" class="nav-link">
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
                  <a href="allResidence.php" class="nav-link ">
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
            <li class="nav-item ">
              <a href="#" class="nav-link">
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
                  <a href="userAdministrator.php" class="nav-link">
                    <i class="fas fa-circle nav-icon text-red"></i>
                    <p>Administrator</p>
                  </a>
                </li>

              </ul>
            </li>
            <li class="nav-item">
              <a href="position.php" class="nav-link bg-indigo">
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
      <section class="content mt-5">
        <div class="container-fluid">

          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <button type="button" class="btn bg-black elevation-5 px-3 btn-flat" id="buttonPosition" data-toggle="modal" data-target="#newModalPosition"><i class="fas fa-plus"></i> ADD POSITION</button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-indigo">SEARCH</span>
                    </div>
                    <input type="text" class="form-control" id="searching" autocomplete="off">

                  </div>
                </div>
              </div>
              <table class="table" id="positionTable">
                <thead>
                  <tr>
                    <th>Position</th>
                    <th>Limit</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
              </table>
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
  <div class="modal fade" id="newModalPosition" data-backdrop="static" data-keybaord="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="newPositionForm" method="post">
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="add_position" id="add_position" class="form-control text-uppercase">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Limit</label>
                    <input type="text" maxlength="2" name="limit" id="limit" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="add_description" id="add_description" rows="3"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-black px-3 elevation-5 btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> CLOSE</button>
            <button type="submit" class="btn bg-success btn-flat px-3 elevation-5"><i class="fas fa-share-square"></i> SAVE</button>
          </div>

        </form>
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
  <script src="../assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <script src="../assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <div id="displayPosition"></div>
  <script>
    $(document).ready(function() {

      positionTable();

      viewStatusPosition();

      function positionTable() {
        var positionTable = $("#positionTable").DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          order: [],
          ajax: {
            url: 'positionTable.php',
            type: 'POST',
          },
          columnDefs: [{
              targets: 2,
              orderable: false,
              className: 'text-center'
            },

          ],
          dom: "<'row'<'col-sm-12 col-md-2'><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'d-flex flex-sm-row-reverse flex-column border-top '<'px-2 'p><'px-2'i> <'px-2'l> >",
          pagingType: "full_numbers",
          language: {
            paginate: {
              next: '<i class="fas fa-angle-right text-white"></i>',
              previous: '<i class="fas fa-angle-left text-white"></i>',
              first: '<i class="fa fa-angle-double-left text-white"></i>',
              last: '<i class="fa fa-angle-double-right text-white"  ></i>'
            },
            lengthMenu: '<div class="mt-3 pr-2"> <span class="text-sm mb-3 pr-2">Rows per page:</span> <select>' +
              '<option value="10">10</option>' +
              '<option value="20">20</option>' +
              '<option value="30">30</option>' +
              '<option value="40">40</option>' +
              '<option value="50">50</option>' +
              '<option value="-1">All</option>' +
              '</select></div>',
            info: " _START_ - _END_ of _TOTAL_ ",
          },
          drawCallback: function(data) {
            $('.dataTables_paginate').addClass("mt-2 mt-md-2 pt-1");
            $('.dataTables_paginate ul.pagination').addClass("pagination-md");
          }
        })
        $('#searching').keyup(function() {
          positionTable.search($(this).val()).draw();
        })
      }

      function editStatusPosition() {
        $(document).on('click', '.editStatusPosition', function() {
          var status_position = $(this).attr('id');

          $.ajax({
            url: 'editStatusposition.php',
            type: 'POST',
            cache: false,
            data: {
              status_position: status_position
            },
            success: function(data) {

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
      }

      function viewStatusPosition() {
        $(document).on('click', '.viewPosition', function() {
          var position_id = $(this).attr('id');

          $("#displayPosition").html('');

          $.ajax({
            url: 'viewPositionModal.php',
            type: 'POST',
            dataType: 'html',
            cache: false,
            data: {
              position_id: position_id
            },
            success: function(data) {
              $("#displayPosition").html(data);
              $("#viewPositionModal").modal('show');
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
      }


      $(function() {
        $.validator.setDefaults({
          submitHandler: function(form) {
            $.ajax({
              url: 'addNewPosition.php',
              type: 'POST',
              data: $(form).serialize(),
              cache: false,
              success: function(data) {
                if (data == 'error') {

                  Swal.fire({
                    title: '<strong class="text-danger"ERROR</strong>',
                    type: 'error',
                    html: '<b>Position is already Exist<b>',
                    width: '400px',
                    confirmButtonColor: '#b30000',
                  })

                } else {



                  Swal.fire({
                    title: '<strong class="text-success">Success</strong>',
                    type: 'success',
                    html: '<b>Added Position has Successfully<b>',
                    width: '400px',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 2000
                  }).then(() => {
                    $("#positionTable").DataTable().ajax.reload();
                    $("#newModalPosition").modal('hide');
                    $("#newPositionForm")[0].reset();
                  })


                }


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
        });
        $('#newPositionForm').validate({
          rules: {
            add_position: {
              required: true,
              minlength: 2
            },
            limit: {
              required: true,

            },

          },
          messages: {
            add_position: {
              required: "Please provide a Position",
              minlength: "Position must be at least 2 characters long"
            },
            limit: {
              required: "Please provide a Limit",
            },

          },
          errorElement: 'span',
          errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            element.closest('.form-group-sm').append(error);
          },
          highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          }
        });
      })


      $(document).on('click', '#buttonPosition', function() {
        $("#newPositionForm")[0].reset();
      })



      $(document).on('click', '.deletePosition', function() {

        var position_id = $(this).attr('id');

        Swal.fire({
          title: '<strong class="text-danger">ARE YOU SURE?</strong>',
          html: "<b>You want delete this Position?</b>",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#b30000',
          cancelButtonColor: '#d33',
          allowOutsideClick: false,
          confirmButtonText: 'Yes, Delete it!',
          width: '400px',
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: 'deletePosition.php',
              type: 'POST',
              data: {
                position_id: position_id,
              },
              cache: false,
              success: function(data) {

                if (data == 'error') {

                  Swal.fire({
                    title: '<strong class="text-danger">ERROR</strong>',
                    type: 'error',
                    html: '<b>This Position have been Used<b>',
                    width: '400px',
                    allowOutsideClick: false,
                  })

                } else {

                  Swal.fire({
                    title: '<strong class="text-success">Success</strong>',
                    type: 'success',
                    html: '<b>Deleted Position has Successfully<b>',
                    width: '400px',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 2000
                  }).then(() => {
                    $("#positionTable").DataTable().ajax.reload();
                  })

                }



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

    $("#add_description").inputFilter(function(value) {
      return /^[a-z, ]*$/i.test(value);
    });


    $("#add_position").inputFilter(function(value) {
      return /^[a-z, ]*$/i.test(value);
    });
    $("#limit").inputFilter(function(value) {
      return /^[0-9]*$/i.test(value);
    });



    $("#limit").on("input", function() {
      if (/^0/.test(this.value)) {
        this.value = this.value.replace(/^0/, "")
      }
    })
  </script>




</body>

</html>
