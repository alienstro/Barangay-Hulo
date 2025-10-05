
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
  <title>Barangay Hulo - Certificate Request</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
      background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      min-height: calc(100vh - 120px);
      padding: 60px 0;
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

    .btn-new-request {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      font-size: 14px;
      padding: 12px 24px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
    }

    .btn-new-request:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
      background: linear-gradient(135deg, #8b0000 0%, #b30000 100%);
      color: white;
    }

    .badge-total {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 600;
      margin-left: 8px;
    }

    .search-container {
      margin-bottom: 24px;
    }

    .input-group-text {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      border: none;
      font-weight: 600;
      border-radius: 12px 0 0 12px;
    }

    .form-control {
      border: 1px solid #ddd;
      border-radius: 0 12px 12px 0;
      padding: 12px 16px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #b30000;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    .btn-reset {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      border: none;
      border-radius: 0 12px 12px 0;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-reset:hover {
      background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
      color: white;
      transform: translateY(-2px);
    }

    .custom-select {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 8px 12px;
      font-size: 13px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.3s ease;
      background: white;
    }

    .custom-select:focus {
      border-color: #b30000;
      box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
    }

    .table {
      font-size: 14px;
    }

    .table thead th {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      font-weight: 600;
      border: none;
      padding: 12px 8px;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
    }

    .table tbody td {
      padding: 12px 8px;
      vertical-align: middle;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(179, 0, 0, 0.05);
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


    .dataTables_wrapper .dataTables_paginate .page-link {
      border: none;
    }

    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
      color: #b30000;
      border-color: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
      color: #fff;
      border: transparent;
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      font-weight: bold;
    }

    .page-link:focus {
      border-color: #CCC;
      outline: 0;
      box-shadow: none;
    }

    .dataTables_length select {
      border: 1px solid #ddd;
      border-radius: 8px;
      cursor: pointer;
      color: #333;
      padding: 4px 8px;
    }

    .dataTables_length span {
      color: #333;
      font-weight: 500;
    }

    .dataTables_info {
      font-size: 13px;
      margin-top: 8px;
      font-weight: 500;
      color: #666;
    }

    .dataTables_scrollHeadInner,
    .table {
      table-layout: auto;
      width: 100% !important;
    }

    #tableRequest_filter {
      display: none;
    }

    @media (max-width: 768px) {
      .cert-container {
        margin: 20px;
      }

      .cert-body {
        padding: 28px 24px;
      }

      .cert-title {
        font-size: 22px;
      }

      .content-wrapper {
        padding: 40px 0;
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
      <div class="container cert-container">
        <div class="cert-card">
          <!-- Header -->
          <div class="cert-header">
            <h1 class="cert-title">Certificate Requests</h1>
            <p class="cert-subtitle">Manage your certificate requests</p>
          </div>

          <!-- Body -->
          <div class="cert-body">
            <!-- Card Header with Title and Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 style="margin: 0; color: #333; font-weight: 600;">
                List of Requests <span class="badge-total" id="total"></span>
              </h4>
              <button type="button" class="btn btn-new-request" data-toggle="modal" data-target="#newRequestModal">
                <i class="fas fa-plus"></i> New Request
              </button>
            </div>

            <!-- Search Bar -->
            <div class="search-container">
              <div class="row">
                <div class="col-sm-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-search"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" id="searching" autocomplete="off" placeholder="Search requests...">
                    <div class="input-group-append">
                      <button class="btn btn-reset" id="reset" type="button">
                        <i class="fas fa-undo"></i> RESET
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="tableRequest">
                <thead>
                  <tr>
                    <th>Purpose</th>
                    <th>
                      <select name="date_request" id="date_request" class="custom-select custom-select-xl">
                        <option value="">Date Request</option>
                        <?php 
                        $blank_request = '';
                        $sql_date_request = "SELECT date_request FROM certificate_request WHERE residence_id = ? AND date_request != ? GROUP BY date_request";
                        $stmt_date_request = $con->prepare($sql_date_request) or die ($con->error);
                        $stmt_date_request->bind_param('ss',$user_id,$blank_request);
                        $stmt_date_request->execute();
                        $result_date_request = $stmt_date_request->get_result();
                        while($row_date_request = $result_date_request->fetch_assoc()){
                          echo '<option value="'.$row_date_request['date_request'].'">'.date("m/d/Y", strtotime($row_date_request['date_request'])).'</option>';
                        }
                        ?>
                      </select>
                    </th>
                    <th>
                      <select name="date_issued" id="date_issued" class="custom-select custom-select-xl">
                        <option value="">Date Issued</option>
                        <?php 
                        $blank_issued = '';
                        $sql_date_issued = "SELECT date_issued FROM certificate_request WHERE residence_id = ? AND date_issued != ? GROUP BY date_issued";
                        $stmt_date_issued = $con->prepare($sql_date_issued) or die ($con->error);
                        $stmt_date_issued->bind_param('ss',$user_id,$blank_issued);
                        $stmt_date_issued->execute();
                        $result_date_issued = $stmt_date_issued->get_result();
                        while($row_date_issued = $result_date_issued->fetch_assoc()){
                          echo '<option value="'.$row_date_issued['date_issued'].'">'.date("m/d/Y", strtotime($row_date_issued['date_issued'])).'</option>';
                        }
                        ?>
                      </select>
                    </th>
                    <th>
                      <select name="date_expired" id="date_expired" class="custom-select custom-select-xl">
                        <option value="">Date Expired</option>
                        <?php 
                        $blank_expired = '';
                        $sql_date_expired = "SELECT date_expired FROM certificate_request WHERE residence_id = ? AND date_expired != ? GROUP BY date_expired";
                        $stmt_date_expired = $con->prepare($sql_date_expired) or die ($con->error);
                        $stmt_date_expired->bind_param('ss',$user_id,$blank_expired);
                        $stmt_date_expired->execute();
                        $result_date_expired = $stmt_date_expired->get_result();
                        while($row_date_expired = $result_date_expired->fetch_assoc()){
                          echo '<option value="'.$row_date_expired['date_expired'].'">'.$row_date_expired['date_expired'].'</option>';
                        }
                        ?>
                      </select>
                    </th>
                    <th>
                      <select name="status" id="status" class="custom-select custom-select-xl">
                        <option value="">Status</option>
                        <?php 
                        $sql_status = "SELECT status FROM certificate_request WHERE residence_id = ? GROUP BY status";
                        $stmt_status = $con->prepare($sql_status) or die ($con->error);
                        $stmt_status->bind_param('s',$user_id);
                        $stmt_status->execute();
                        $result_status = $stmt_status->get_result();
                        while($row_status = $result_status->fetch_assoc()){
                          echo '<option value="'.$row_status['status'].'">'.$row_status['status'].'</option>';
                        }
                        ?>
                      </select>
                    </th>
                    <th class="text-center">Tools</th>
                  </tr>
                </thead>
                <tbody></tbody>
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

<!-- Modal -->
<div class="modal fade" id="newRequestModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 16px; border: none;">
      <form id="requestForm" method="post">
        <div class="modal-header" style="background: linear-gradient(135deg, #b30000 0%, #8b0000 100%); border-radius: 16px 16px 0 0; border: none;">
          <h5 class="modal-title" style="color: white; font-weight: 600;">
            <i class="fas fa-file-alt"></i> New Certificate Request
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 24px;">
          <div class="container-fluid">
            <div class="row">
              <input type="hidden" name="user_id" id="user_id" value="<?= $user_id;?>">
              <div class="col-sm-12">
                <div class="form-group">
                  <label style="font-weight: 600; color: #333;">Purpose</label>
                  <input type="text" name="purpose" id="purpose" class="form-control text-uppercase" required placeholder="Enter certificate purpose..." style="border-radius: 12px; padding: 12px 16px;">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border: none; padding: 16px 24px;">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 12px; padding: 10px 24px; font-weight: 600;">
            <i class="fas fa-times"></i> Close
          </button>
          <button type="submit" class="btn btn-new-request" style="padding: 10px 24px;">
            <i class="fas fa-paper-plane"></i> Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="show_status"></div>


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

<script>
  $(document).ready(function(){

    tableRequest()

    function tableRequest(){

      var date_request =  $("#date_request").val();
      var date_issued  =  $("#date_issued").val();
      var date_expired =  $("#date_expired").val();
      var status       =  $("#status").val();
      var user_id      = $("#user_id").val();
      var tableRequest = $("#tableRequest").DataTable({
        processing: true,
        serverSide: true,
        order:[],
        autoWidth: false,
        ordering: false,
        columnDefs:[{
              targets: 5,
              className: 'text-center'
        }],
        dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'d-flex flex-sm-row-reverse flex-column border-top '<'px-2 'p><'px-2'i> <'px-2'l> >",
            pagingType: "full_numbers",
            language: {
              paginate: {
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>', 
                first: '<i class="fa fa-angle-double-left"></i>',
                last: '<i class="fa fa-angle-double-right"></i>'        
              }, 
              lengthMenu: '<div class="mt-3 pr-2"> <span class="text-sm mb-3 pr-2">Rows per page:</span> <select>'+
                          '<option value="10">10</option>'+
                          '<option value="20">20</option>'+
                          '<option value="30">30</option>'+
                          '<option value="40">40</option>'+
                          '<option value="50">50</option>'+
                          '<option value="-1">All</option>'+
                          '</select></div>',
              info:  " _START_ - _END_ of _TOTAL_ ",
            },
        ajax:{
            url: 'userRequestTable.php',
            type: 'POST',
            data:{
              user_id:user_id,
              date_request:date_request,
              date_issued:date_issued,
              date_expired:date_expired,
              status:status,
            }
        },
            drawCallback:function(data)  {
              $('#total').text(data.json.total);
              $('.dataTables_paginate').addClass("mt-2 mt-md-2 pt-1");
              $('.dataTables_paginate ul.pagination').addClass("pagination-md");   
              $('[data-toggle="tooltip"]').tooltip();
                               
            },
       
      })
      $('#searching').keyup(function(){
        tableRequest.search($(this).val()).draw() ;
        })

    }
    

    $(document).on('change',"#date_request, #date_issued, #date_expired, #status",function(){
      $("#tableRequest").DataTable().destroy();
      tableRequest()
      $('#searching').keyup();
    })

    



  $("#requestForm").submit(function(e){
    e.preventDefault();

    Swal.fire({
        title: '<strong class="text-info">ARE YOU SURE?</strong>',
        html: "<b>You want Submit this Request?</b>",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#b30000',
        cancelButtonColor: '#d33',
        allowOutsideClick: false,
        confirmButtonText: 'Yes, Submit it!',
        width: '400px',
      }).then((result) => {
        if (result.value) {
            $.ajax({
              url: 'requestCertificate.php',
              type: 'POST',
              data: $(this).serialize(),
              success:function(){

                  Swal.fire({
                    title: '<strong class="text-success">Success</strong>',
                    type: 'success',
                    html: '<b>Request Submitted  Successfully<b>',
                    width: '400px',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 2000
                  }).then(()=>{
                    $("#requestForm")[0].reset();
                    $("#tableRequest").DataTable().ajax.reload();
                    $("#newRequestModal").modal('hide')
                  })

                  
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
        }
      })

  })



    $(document).on('click','#reset',function(){

        if($("#date_request").val() != '' ||  $("#date_issued").val() !=  '' || $("#date_expired").val() != '' ||  $("#status").val() != '' ||  $("#searching").val() != ''){
            $("#date_request").val('');
            $("#date_issued").val('');
            $("#date_expired").val('');
            $("#status").val('');
            $("#searching").val('');
            $("#tableRequest").DataTable().destroy();
            tableRequest();
              $("#searching").keyup();
        }
    })


    $(document).on('click','.acceptStatus',function(){

        $("#show_status").html('');

        var residence_id = $(this).attr('id');
        var certificate_id = $(this).data('id');

        $.ajax({
          url: 'certificateRequestStatus.php',
          type: 'POST',
          data:{
            residence_id:residence_id,
            certificate_id:certificate_id,
          },
          success:function(data){
            $("#show_status").html(data);
            $("#showStatusRequestModal").modal('show');
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



  $("#purpose").inputFilter(function(value) {
  return /^[a-z, ]*$/i.test(value); 
  });
  


</script>


</body>
</html>
