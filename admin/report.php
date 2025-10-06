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
    $first_name_user = $row_user['first_name'] ?? '';
    $last_name_user = $row_user['last_name'] ?? '';
    $user_type = $row_user['user_type'] ?? '';
    $user_image = $row_user['image'] ?? '';



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

    $table = '';

    if (isset($_POST['submit'])) {



      $whereClause = [];

      $voters = $con->real_escape_string($_POST['voters']);
      $age = $con->real_escape_string($_POST['age']);
      $status = $con->real_escape_string($_POST['status']);
      $pwd = $con->real_escape_string($_POST['pwd']);
      $senior = $con->real_escape_string($_POST['senior']);
      $single_parent = $con->real_escape_string($_POST['single_parent']);

      if (!empty($voters))
        $whereClause[] = "residence_status.voters='$voters'";

      if (!empty($age))
        $whereClause[] = "residence_information.age='$age'";

      if (!empty($status))
        $whereClause[] = "residence_status.status='$status'";

      if (!empty($pwd))
        $whereClause[] = "residence_status.pwd='$pwd'";

      if (!empty($single_parent))
        $whereClause[] = "residence_status.single_parent='$single_parent'";

      if (!empty($senior))
        $whereClause[] = "residence_status.senior='$senior'";

      $where = '';

      if (count($whereClause) > 0) {
        $where .= ' AND ' . implode(' AND ', $whereClause);
      }


      $sql_report = "SELECT residence_information.*, residence_status.* FROM residence_information 
        INNER JOIN residence_status ON residence_information.residence_id =  residence_status.residence_id WHERE archive = 'NO'" . $where;
      $query_report = $con->query($sql_report) or die($con->error);
      $count_report = $query_report->num_rows;
      if ($count_report > 0) {



        while ($row_report = $query_report->fetch_assoc()) {

          if ($row_report['middle_name'] != '') {
            $middle_name = ucfirst($row_report['middle_name'])[0] . '.';
          } else {
            $middle_name = $row_report['middle_name'];
          }


          $table .= '<tr>
                    <td>' . ucfirst($row_report['last_name']) . ' ' . ucfirst($row_report['first_name']) . '  ' . $middle_name . ' </td>
                    <td>' . $row_report['age'] . '</td>
                    <td>' . $row_report['pwd_info'] . '</td>
                    <td>' . $row_report['single_parent'] . '</td>
                    <td>' . $row_report['voters'] . '</td>
                    <td>' . $row_report['status'] . '</td>
                    <td>' . $row_report['senior'] . '</td>
                </tr>';
        }
      } else {

        $table .= '<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
      }
    } else {



      $sql_report = "SELECT residence_information.*, residence_status.* FROM residence_information 
      INNER JOIN residence_status ON residence_information.residence_id =  residence_status.residence_id WHERE archive ='NO'";
      $query_report = $con->query($sql_report) or die($con->error);
      while ($row_report = $query_report->fetch_assoc()) {

        if ($row_report['middle_name'] != '') {
          $middle_name = ucfirst($row_report['middle_name'])[0] . '.';
        } else {
          $middle_name = $row_report['middle_name'];
        }

        $table .= '<tr>
      <td>' . ucfirst($row_report['last_name'] ?? '') . ' ' . ucfirst($row_report['first_name'] ?? '') . '  ' . $middle_name . ' </td>
              <td>' . $row_report['age'] ?? '' . '</td>
              <td>' . $row_report['pwd_info'] ?? '' . '</td>
              <td>' . $row_report['single_parent'] ?? '' . '</td>
              <td>' . $row_report['voters'] ?? '' . '</td>
              <td>' . $row_report['status'] ?? '' . '</td>
              <td>' . $row_report['senior'] ?? '' . '</td>
          </tr>';
      }
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
  <link rel="stylesheet" href="../assets/plugins/jquery-ui/jquery-ui.min.css">
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .modal-body {
      height: 80vh;
      overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar {
      width: 5px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: #b30000;
      --webkit-box-shadow: inset 0 0 6px #b30000;
    }

    .modal-body::-webkit-scrollbar-thumb:window-inactive {
      background: #8b0000;
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

      .modal-body {
        height: 70vh;
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

      .modal-body {
        height: 60vh;
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
        height: 50vh;
      }
    }
  </style>

</head>

<body class="hold-transition sidebar-mini sidebar-collapse   layout-footer-fixed">
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
              <a href="report.php" class="nav-link bg-indigo">
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
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">

            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">


          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title">Resident Report</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body ">
              <form action="report.php" method="post">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">VOTERS</span>
                      </div>
                      <select name="voters" id="voters" class="form-control">
                        <option value="">--SELECT VOTERS--</option>
                        <option value="YES" <?php if (isset($voters) && $voters == 'YES') echo 'selected'; ?>>YES</option>
                        <option value="NO" <?php if (isset($voters) && $voters == 'NO') echo 'selected'; ?>>NO</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">AGE</span>
                      </div>
                      <input type="number" name="age" id="age" class="form-control" value="<?php if (isset($age)) echo $age; ?>">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">STATUS</span>
                      </div>
                      <select name="status" id="status" class="form-control">
                        <option value="">--SELECT STATUS--</option>
                        <option value="ACTIVE" <?php if (isset($status) && $status == 'ACTIVE') echo 'selected'; ?>>ACTIVE</option>
                        <option value="INACTIVE" <?php if (isset($status) && $status == 'INACTIVE') echo 'selected'; ?>>INACTIVE</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">PWD</span>
                      </div>
                      <select name="pwd" id="pwd" class="form-control">
                        <option value="">--SELECT PWD--</option>
                        <option value="YES" <?php if (isset($pwd) && $pwd == 'YES') echo 'selected'; ?>>YES</option>
                        <option value="NO" <?php if (isset($pwd) && $pwd == 'NO') echo 'selected'; ?>>NO</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">SINGLE PARENT</span>
                      </div>
                      <select name="single_parent" id="single_parent" class="form-control">
                        <option value="">--SELECT PARENT STATUS--</option>
                        <option value="YES" <?php if (isset($single_parent) && $single_parent == 'YES') echo 'selected'; ?>>YES</option>
                        <option value="NO" <?php if (isset($single_parent) && $single_parent == 'NO') echo 'selected'; ?>>NO</option>
                      </select>
                    </div>
                  </div>


                  <div class="col-sm-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-indigo">SENIOR</span>
                      </div>
                      <select name="senior" id="senior" class="form-control">
                        <option value="">--SELECT SENIOR--</option>
                        <option value="YES" <?php if (isset($senior) && $senior == 'YES') echo 'selected'; ?>>YES</option>
                        <option value="NO" <?php if (isset($senior) && $senior == 'NO') echo 'selected'; ?>>NO</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center ">
                    <button type="submit" class="btn btn-flat bg-info px-3 elevation-3 text-white" name="submit" id="search"><i class="fas fa-filter"></i> FILTER</button>
                    <a href="report.php" class="btn btn-flat btn-danger px-3 elevation-3" id="reset"><i class="fas fa-undo"></i> RESET</a>
                  </div>
              </form>
            </div>
            <div class="form-group">
              <a href="printReport.php?<?php

                                        if (isset($_POST['submit'])) {

                                          $whereClauses = [];

                                          $voters = $con->real_escape_string($_POST['voters']);
                                          $age = $con->real_escape_string($_POST['age']);
                                          $status = $con->real_escape_string($_POST['status']);
                                          $pwd = $con->real_escape_string($_POST['pwd']);
                                          $senior = $con->real_escape_string($_POST['senior']);

                                          $single_parent = $con->real_escape_string($_POST['single_parent']);


                                          if (!empty($voters))
                                            $whereClauses[] = "voters=$voters";

                                          if (!empty($age))
                                            $whereClauses[] = "age=$age";

                                          if (!empty($status))
                                            $whereClauses[] = "status=$status";

                                          if (!empty($pwd))
                                            $whereClauses[] = "pwd=$pwd";

                                          if (!empty($senior))
                                            $whereClauses[] = "senior=$senior";


                                          if (!empty($single_parent))
                                            $whereClauses[] = "single_parent=$single_parent";

                                          $wheres = '';

                                          if (count($whereClauses) > 0) {
                                            $wheres .= implode('&', $whereClauses);
                                          }
                                          echo $wheres;
                                        }



                                        ?>" target="_blank" class="btn btn-warning btn-flat elevation-5 px-3"><i class="fas fa-print"></i> PRINT</a>

            </div>
            <table class="table table-striped table-hover table-sm" id="tableReport">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Age</th>
                  <th>Pwd</th>
                  <th>Single Parent</th>
                  <th>Voters</th>
                  <th>Status</th>
                  <th>Senior</th>
                </tr>
              </thead>
              <tbody>
                <?= $table  ?>
              </tbody>
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



  <script>
    $(document).ready(function() {

      var table = $("#tableReport").DataTable({
        searching: false,

        info: false,
        ordering: false,
        lengthChange: false,

      })

      $("#age").on("input", function() {
        if (/^0/.test(this.value)) {
          this.value = this.value.replace(/^0/, "")
        }
      })


      //   $(document).on('click','.print',function(){


      //   var printContents = $("#printReport").html();

      //     var originalContents = document.body.innerHTML;
      //     document.body.innerHTML = printContents;
      //     window.print();
      //     document.body.innerHTML = originalContents;
      //     window.location.reload();
      // })


    })
  </script>

</body>

</html>
