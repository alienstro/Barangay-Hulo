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


    $sql_resident = "SELECT * FROM residence_information WHERE residence_id = '$user_id'";
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
  <title>Barangay Hulo - My Blotter Records</title>

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
      padding: 60px 20px;
    }

    .cert-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .cert-card {
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

    .cert-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 32px 24px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cert-title {
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

    .cert-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 16px;
      margin: 0;
    }

    .cert-body {
      background: white;
      padding: 36px 32px;
    }

    #myRecordTable {
      margin: 0 !important;
      width: 100% !important;
      border-collapse: separate;
      border-spacing: 0;
    }

    #myRecordTable thead {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
    }

    #myRecordTable thead th {
      color: white !important;
      font-weight: 600;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 20px 16px !important;
      border: none !important;
      white-space: nowrap;
    }

    #myRecordTable tbody td {
      padding: 18px 16px !important;
      font-size: 14px;
      vertical-align: middle;
      border-bottom: 1px solid #f0f0f0 !important;
      border-left: none !important;
      border-right: none !important;
      color: white !important;
      font-weight: 500;
    }

    #myRecordTable tbody tr {
      transition: all 0.3s ease;
    }

    #myRecordTable tbody tr:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-view {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      border: none;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .btn-view:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
      color: white;
    }

    .btn-view i {
      margin-right: 6px;
    }

    .dataTables_wrapper .dataTables_paginate .page-link {
      border: none;
      background: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      color: #b30000;
      border-color: transparent;
      font-weight: 500;
      padding: 8px 16px;
      margin: 0 4px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
      color: #fff;
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
      border: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .page-item:hover .page-link {
      background: rgba(179, 0, 0, 0.1);
      color: #b30000;
    }

    .page-link:focus {
      border-color: transparent;
      outline: 0;
      box-shadow: none;
    }

    .dataTables_length select {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 8px 30px 8px 12px;
      cursor: pointer;
      color: #333;
      font-weight: 500;
      background: white;
      transition: all 0.3s ease;
    }

    .dataTables_length select:focus {
      border-color: #b30000;
      outline: none;
    }

    .dataTables_length label {
      color: #333;
      font-weight: 500;
      font-size: 14px;
    }

    .dataTables_info {
      font-size: 14px;
      margin-top: 8px;
      font-weight: 500;
      color: #666;
    }

    .dataTables_scrollHeadInner, 
    .table { 
      table-layout: auto;
      width: 100% !important; 
    }

    .select2-container--default .select2-selection--single {
      background-color: white;
      height: 38px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #333;
    }

    #tableRequest_filter {
      display: none;
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
        padding: 40px 15px;
      }

      .cert-container {
        max-width: 1200px;
      }

      .cert-body {
        padding: 32px 28px;
      }

      #myRecordTable thead th {
        font-size: 12px;
        padding: 16px 12px !important;
      }

      #myRecordTable tbody td {
        padding: 14px 12px !important;
        font-size: 13px;
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

      .cert-container {
        max-width: 100%;
        margin: 0;
      }

      .cert-card {
        border-radius: 20px;
      }

      .cert-header {
        padding: 28px 20px;
      }

      .cert-title {
        font-size: 24px;
      }

      .cert-subtitle {
        font-size: 15px;
      }

      .cert-body {
        padding: 28px 20px;
      }

      .table-responsive {
        border-radius: 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      #myRecordTable {
        font-size: 12px;
      }

      #myRecordTable thead th {
        font-size: 11px;
        padding: 12px 8px !important;
        white-space: normal;
      }

      #myRecordTable tbody td {
        padding: 12px 8px !important;
        font-size: 12px;
        white-space: normal;
      }

      .btn-view {
        padding: 6px 12px;
        font-size: 11px;
      }

      .dataTables_wrapper .dataTables_paginate .page-item .page-link {
        padding: 6px 10px;
        font-size: 12px;
        margin: 0 2px;
      }

      .dataTables_length select {
        padding: 6px 20px 6px 10px;
        font-size: 12px;
      }

      .dataTables_info {
        font-size: 12px;
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

      .cert-card {
        border-radius: 16px;
      }

      .cert-header {
        padding: 24px 16px;
      }

      .cert-title {
        font-size: 22px;
        letter-spacing: 0.5px;
      }

      .cert-subtitle {
        font-size: 14px;
      }

      .cert-body {
        padding: 24px 16px;
      }

      #myRecordTable thead th {
        font-size: 10px;
        padding: 10px 6px !important;
      }

      #myRecordTable tbody td {
        padding: 10px 6px !important;
        font-size: 11px;
      }

      .btn-view {
        padding: 5px 10px;
        font-size: 10px;
      }

      .btn-view i {
        margin-right: 4px;
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

      .cert-header {
        padding: 20px 14px;
      }

      .cert-title {
        font-size: 20px;
      }

      .cert-subtitle {
        font-size: 13px;
      }

      .cert-body {
        padding: 20px 14px;
      }

      #myRecordTable thead th,
      #myRecordTable tbody td {
        font-size: 10px;
        padding: 8px 4px !important;
      }

      .btn-view {
        padding: 4px 8px;
        font-size: 9px;
      }
    }

    /* Landscape Mobile Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
      .content-wrapper {
        padding: 20px 15px;
        min-height: auto;
      }

      .cert-header {
        padding: 16px;
      }

      .cert-title {
        font-size: 20px;
      }

      .cert-subtitle {
        font-size: 13px;
        margin-top: 4px;
      }

      .cert-body {
        padding: 20px 16px;
      }

      #myRecordTable thead th,
      #myRecordTable tbody td {
        padding: 6px 4px !important;
      }
    }

    /* DataTables Responsive Enhancements */
    @media (max-width: 767px) {
      .dataTables_wrapper .dataTables_paginate {
        text-align: center;
        margin-top: 12px;
      }

      .dataTables_wrapper .dataTables_paginate .pagination {
        justify-content: center;
        flex-wrap: wrap;
      }

      .dataTables_wrapper .dataTables_length {
        text-align: center;
        margin-bottom: 10px;
      }

      .dataTables_wrapper .dataTables_info {
        text-align: center;
        padding: 8px 0;
      }

      .dataTables_wrapper .row {
        margin: 0;
      }

      .dataTables_wrapper .col-sm-12 {
        padding: 0;
      }
    }

    /* Ensure proper touch targets on mobile */
    @media (hover: none) and (pointer: coarse) {
      .nav-link,
      .btn-view {
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
      <div class="container cert-container">
        <div class="cert-card">
          <!-- Header -->
          <div class="cert-header">
            <h1 class="cert-title">My Blotter Records</h1>
            <p class="cert-subtitle">View your blotter records and incident reports</p>
          </div>

          <!-- Body -->
          <div class="cert-body">
            <input type="hidden" value="<?=$user_id; ?>" id="edit_residence_id">
            
            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="myRecordTable">
                <thead>
                  <tr>
                    <th class="d-none test">Color</th>
                    <th>Blotter Number</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Incident</th>
                    <th>Location</th>
                    <th>Date Incident</th>
                    <th>Date Reported</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
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

<div id="show_records"></div>

<script>
  $(document).ready(function(){

    blotterPersonTable()

    function blotterPersonTable(){

var edit_residence_id = $("#edit_residence_id").val();
var blotterPersonTable = $("#myRecordTable").DataTable({
 
  processing: true,
  serverSide: true,
  responsive: true,
  order:[],
  searching: false,
  info: false,
  paging: false,
  lengthChange: false,
  autoWidth: false,
  columnDefs:[
    {
      targets: '_all',
      orderable: false,
    },

    {
      targets: 0,
     className: 'd-none',
    }
    
  ],
  ajax:{
    url: 'myRecordTable.php',
    type: 'POST',
    data:{
      edit_residence_id:edit_residence_id
    }
  },
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
          if ( aData[0] == "1" )  {
          $('td', nRow).css('background-color', '#20c997');
        
        }else {
          $('td', nRow).css('background-color', '#000');
          }
          
      },
   
  

})

  
}


$(document).on('click','.viewRecords', function(){

var record_id = $(this).attr('id');


$("#show_records").html('');

  $.ajax({
    url: 'viewRecordsModal.php',
    type: 'POST',
    data:{
      record_id:record_id,
    },
    cache: false,
    success:function(data){
      $("#show_records").html(data);
      $("#viewBlotterRecordModal").modal('show');

    }
  }).fail(function(){
      Swal.fire({
        title: '<strong class="text-danger">Ooppss..</strong>',
        type: 'error',
        html: '<b>Something went wrong with ajax !<b>',
        width: '400px',
        confirmButtonColor: '#b30000',
      })
  })
})


  })
</script>
</body>
</html>
