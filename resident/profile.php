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
    $username = $row_user['username'];
    $old_password = $row_user['password'];
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];


    $sql_resident = "SELECT * FROM residence_information WHERE residence_id = '$user_id'";
    $query_resident = $con->query($sql_resident) or die ($con->error);
    $row_resident = $query_resident->fetch_assoc();


    if($row_resident['image'] != ''){
      $iamge_resident = $row_resident['image_path'];
    }else{
      $iamge_resident = '../assets/dist/img/blank_image.png';
    }



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
  <title>Profile - Barangay Hulo</title>

  <link rel="preload" href="../assets/logo/cover.JPG" as="image"> 
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
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
      background-color: rgba(0,0,0,0.40);
      background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url('../assets/logo/cover.JPG');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-blend-mode: overlay;
      min-height: calc(100vh - 120px);
      padding: 40px 0;
    }

    .profile-container {
      max-width: 800px;
      margin: 30px auto; 
    }

    .profile-card {
      width: calc(100% - 48px);
      max-width: 760px; 
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
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

    .profile-header {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      padding: 24px 20px; /* reduced vertical padding */
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .profile-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .profile-image-container {
      position: relative;
      width: 120px; /* smaller image to reduce header height */
      height: 120px;
      margin: 0 auto 14px;
      z-index: 1;
    }

    .profile-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 5px solid white;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
      object-fit: cover;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .resident-number {
      color: white;
      font-size: 16px; /* slightly smaller */
      font-weight: 600;
      position: relative;
      z-index: 1;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-body {
      padding: 28px 22px; /* reduced padding for compact card */
    }

    .section-title {
      font-size: 18px; /* slightly smaller */
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f0f0f0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #b30000;
      font-size: 22px;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .input-group {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      box-shadow: 0 4px 16px rgba(179, 0, 0, 0.2);
      transform: translateY(-2px);
    }

    .input-group-text {
      background: white;
      color: #b30000;
      font-size: 18px;
      border: none;
    }

    .form-control {
      border: none;
      padding: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .input-group-append .input-group-text {
      border-left: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .input-group-append .input-group-text:hover {
      background: #f8f9fa;
    }

    .input-group-append a {
      color: #666;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .input-group-append a:hover {
      color: #b30000;
    }

    .btn-submit {
      background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 15px;
      font-size: 16px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(179, 0, 0, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.2);
      transition: left 0.5s ease;
    }

    .btn-submit:hover::before {
      left: 100%;
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 25px rgba(179, 0, 0, 0.4);
    }

    .btn-submit:active {
      transform: translateY(-1px);
    }

    footer.main-footer {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      color: #333;
      text-align: center;
      padding: 20px 0;
      font-weight: 500;
      border-top: 3px solid #b30000;
    }

    footer .fas {
      color: #b30000;
      margin-right: 8px;
    }

    .invalid-feedback {
      color: #dc3545;
      font-size: 13px;
      margin-top: 5px;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    @media (max-width: 768px) {
      .profile-body {
        padding: 20px 16px;
      }

      .profile-header {
        padding: 20px 16px;
      }

      .profile-image {
        width: 100px;
        height: 100px;
      }

      .section-title {
        font-size: 17px;
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
            <a href="profile.php" class="nav-link active">
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
        <div class="profile-container">
          <div class="profile-card">
            <div class="profile-header">
              <div class="profile-image-container">
                <img src="<?= $iamge_resident ?>" alt="Resident Image" class="profile-image">
              </div>
              <div class="resident-number">
                <i class="fas fa-id-card"></i> Resident ID: <?= $user_id ?>
              </div>
            </div>

            <div class="profile-body">
              <form id="changeProfile" method="post">
                <div class="section-title">
                  <i class="fas fa-user-edit"></i>
                  <span>Account Settings</span>
                </div>

                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?= $username ?>">
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group" id="show_hide_password_old">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                      </span>
                    </div>
                    <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Current Password">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <a href="#"><i class="fas fa-eye-slash"></i></a>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group" id="show_hide_password">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <a href="#"><i class="fas fa-eye-slash"></i></a>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group" id="show_hide_password_confirm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-check-circle"></i>
                      </span>
                    </div>
                    <input type="password" id="edit_confirm_password" name="edit_confirm_password" class="form-control" placeholder="Confirm New Password">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <a href="#"><i class="fas fa-eye-slash"></i></a>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-0">
                  <button type="submit" class="btn btn-submit btn-block">
                    <i class="fas fa-save"></i> Update Profile
                  </button>
                </div>
              </form>
            </div>
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
<script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

<script>

  $(document).ready(function(){

    
 $(function () {
        $.validator.setDefaults({
          submitHandler: function (form) {

              var newPassword = $("#new_password").val();
              var edit_confirm_password = $("#edit_confirm_password").val();

              if(newPassword != edit_confirm_password){


                  
                        Swal.fire({
                            title: '<strong class="text-danger">ERROR</strong>',
                            icon: 'error',
                            html: '<b>New password and confirm password do not match<b>',
                            width: '400px',
                            confirmButtonColor: '#b30000',
                          })



              }else{


                $.ajax({
                    url: 'changeProfile.php',
                    type: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success:function(data){

                      if(data == 'error1'){
                          Swal.fire({
                            title: '<strong class="text-danger">ERROR</strong>',
                            icon: 'error',
                            html: '<b>Username already exists<b>',
                            width: '400px',
                            confirmButtonColor: '#b30000',
                          })
                      }else if(data == 'error2'){

                        Swal.fire({
                            title: '<strong class="text-danger">ERROR</strong>',
                            icon: 'error',
                            html: '<b>Current password is incorrect<b>',
                            width: '400px',
                            confirmButtonColor: '#b30000',
                          })

                      }else{
                        
                        Swal.fire({
                          title: '<strong class="text-success">SUCCESS</strong>',
                          icon: 'success',
                          html: '<b>Profile updated successfully<b>',
                          width: '400px',
                          confirmButtonColor: '#b30000',
                          allowOutsideClick: false,
                          showConfirmButton: false,
                          timer: 2000,
                        }).then(()=>{
                          $("#old_password").val('');
                          $("#new_password").val('');
                          $("#edit_confirm_password").val('');
                          
                       

                        })
                        

                      
                      }
                      
                    }
                }).fail(function(){
                    Swal.fire({
                      title: '<strong class="text-danger">Oops...</strong>',
                      icon: 'error',
                      html: '<b>Something went wrong!<b>',
                      width: '400px',
                      confirmButtonColor: '#b30000',
                    })
                })

              }

               

           
          }
        });
      $('#changeProfile').validate({
  
        rules: {
          username: {
            required: true,
            minlength: 6
          },
          old_password: {
            required: true,
         
          },
          new_password: {
            minlength: 6
         
          },
         
        
        
        },
        messages: {
          username: {
            required: "This field is required",
            minlength: "Username must be at least 6 characters"
          },
          old_password: {
            required: "This field is required",
        
          },
          new_password: {
            minlength: "Password must be at least 6 characters",
        
          },
        
         
          
            
        },
   
     
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },
      
      });
      
    })
    
   
$("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
    $("#show_hide_password_confirm a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_confirm input').attr("type") == "text"){
            $('#show_hide_password_confirm input').attr('type', 'password');
            $('#show_hide_password_confirm i').addClass( "fa-eye-slash" );
            $('#show_hide_password_confirm i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_confirm input').attr("type") == "password"){
            $('#show_hide_password_confirm input').attr('type', 'text');
            $('#show_hide_password_confirm i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_confirm i').addClass( "fa-eye" );
        }
    });
    $("#show_hide_password_old a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_old input').attr("type") == "text"){
            $('#show_hide_password_old input').attr('type', 'password');
            $('#show_hide_password_old i').addClass( "fa-eye-slash" );
            $('#show_hide_password_old i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_old input').attr("type") == "password"){
            $('#show_hide_password_old input').attr('type', 'text');
            $('#show_hide_password_old i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_old i').addClass( "fa-eye" );
        }
    });
  })
</script>


</body>
</html>