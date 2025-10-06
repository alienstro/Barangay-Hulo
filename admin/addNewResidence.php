<?php 

include_once '../connection.php';



try{


date_default_timezone_set('Asia/Manila');
$date = new DateTime();
$number = rand($date->format("mdyHisv"),true);
$date_added = date("m/d/Y h:i A");
$archive = 'NO';


if(isset($_POST['add_pwd_info'])){
  $add_pwd_check = $con->real_escape_string($_POST['add_pwd_info']);
}else{
  $add_pwd_check = '';
}


$add_pwd = $con->real_escape_string($_POST['add_pwd']);
$add_single_parent = $con->real_escape_string($_POST['add_single_parent']);
$add_voters = $con->real_escape_string($_POST['add_voters']);
$add_first_name = $con->real_escape_string($_POST['add_first_name']);
$add_middle_name = $con->real_escape_string($_POST['add_middle_name']);
$add_last_name = $con->real_escape_string($_POST['add_last_name']);
$add_suffix = $con->real_escape_string($_POST['add_suffix']);
$add_gender = $con->real_escape_string($_POST['add_gender']);
$add_civil_status = $con->real_escape_string($_POST['add_civil_status']);
$add_religion = $con->real_escape_string($_POST['add_religion']);
$add_nationality = $con->real_escape_string($_POST['add_nationality']);
$add_contact_number = $con->real_escape_string($_POST['add_contact_number']);
$add_email_address = $con->real_escape_string($_POST['add_email_address']);
$add_address = $con->real_escape_string($_POST['add_address']);
$add_birth_date = $con->real_escape_string($_POST['add_birth_date']);
$add_birth_place = $con->real_escape_string($_POST['add_birth_place']);
$add_municipality = $con->real_escape_string($_POST['add_municipality']);
$add_zip = $con->real_escape_string($_POST['add_zip']);
$add_barangay = $con->real_escape_string($_POST['add_barangay']);
$add_house_number = $con->real_escape_string($_POST['add_house_number']);
$add_street = $con->real_escape_string($_POST['add_street']);
$add_fathers_name = $con->real_escape_string($_POST['add_fathers_name']);
$add_mothers_name = $con->real_escape_string($_POST['add_mothers_name']);
$add_guardian = $con->real_escape_string($_POST['add_guardian']);
$add_guardian_contact = $con->real_escape_string($_POST['add_guardian_contact']);
$add_image = $con->real_escape_string($_FILES['add_image']['name']);
$add_status = 'ACTIVE';
$user_type = 'resident';
$password = $date->format("mdYHisv");

// Allow admin to optionally specify an initial password (plain text, per request)
if (isset($_POST['add_password']) && trim($_POST['add_password']) !== '') {
  $posted_password = $con->real_escape_string($_POST['add_password']);
} else {
  $posted_password = '';
}

// Username submitted from form (required)
if(isset($_POST['add_username']) && trim($_POST['add_username']) !== ''){
  $add_username = $con->real_escape_string($_POST['add_username']);
}else{
  echo json_encode(array('status' => 'error', 'message' => 'Username is required'));
  exit;
}
if(isset($add_image)){
  if($add_image != '' || $add_image != null || !empty($add_image)){
    $type = explode('.', $add_image);
    $type = $type[count($type) -1];
    $new_image_name = uniqid(rand()) .'.'. $type;
    $new_image_path = '../assets/dist/img/' . $new_image_name;
    move_uploaded_file($_FILES['add_image']['tmp_name'],$new_image_path);
  }else{
    $new_image_name = '';
    $new_image_path = '';
  }
}

$today = date("Y/m/d");
$age = date_diff(date_create($add_birth_date), date_create($today));
$add_age_date = $age->format("%y");

if($add_age_date >= '60'){
  $senior = 'YES';
}else{
  $senior = 'NO';
}

if($add_age_date == '0'){
  $age_add = '';
}else{
  $age_add = $add_age_date;
}

// Server-side email validation and duplicate check (before any inserts)
if (!empty($add_email_address)) {
  // validate format
  if (!filter_var($add_email_address, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid email address'));
    exit;
  }

  // check users table for duplicate email
  $checkEmailSql = "SELECT `id` FROM `users` WHERE `email` = ? LIMIT 1";
  $checkEmailStmt = $con->prepare($checkEmailSql) or die($con->error);
  $checkEmailStmt->bind_param('s', $add_email_address);
  $checkEmailStmt->execute();
  $checkEmailStmt->store_result();
  if ($checkEmailStmt->num_rows > 0) {
    echo json_encode(array('status' => 'error', 'message' => 'Email already in use'));
    exit;
  }
  $checkEmailStmt->close();

  // check residence_information for duplicate email
  $checkResSql = "SELECT `residence_id` FROM `residence_information` WHERE `email_address` = ? LIMIT 1";
  $checkResStmt = $con->prepare($checkResSql) or die($con->error);
  $checkResStmt->bind_param('s', $add_email_address);
  $checkResStmt->execute();
  $checkResStmt->store_result();
  if ($checkResStmt->num_rows > 0) {
    echo json_encode(array('status' => 'error', 'message' => 'Email already in use'));
    exit;
  }
  $checkResStmt->close();

}



$sql = "INSERT INTO `residence_information`( `residence_id`,`first_name`, `middle_name`, `last_name`, `age`, `suffix`, `gender`, `civil_status`, `religion`, `nationality`, `contact_number`, `email_address`, `address`, `birth_date`, `birth_place`, `municipality`, `zip`, `barangay`, `house_number`, `street`, `fathers_name`, `mothers_name`, `guardian`, `guardian_contact`,`image`,`image_path`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $con->prepare($sql) or die ($con->error);
$stmt->bind_param('ssssssssssssssssssssssssss',
  $number,
  $add_first_name,
  $add_middle_name,
  $add_last_name,
  $age_add,
  $add_suffix,
  $add_gender,
  $add_civil_status,
  $add_religion,
  $add_nationality,
  $add_contact_number,
  $add_email_address,
  $add_address,
  $add_birth_date,
  $add_birth_place,
  $add_municipality,
  $add_zip,
  $add_barangay,
  $add_house_number,
  $add_street,
  $add_fathers_name,
  $add_mothers_name,
  $add_guardian,
  $add_guardian_contact,
  $new_image_name,
  $new_image_path
);
$stmt->execute();
$stmt->close();

$sql_residence_status = "INSERT INTO `residence_status` (`residence_id`, `status`, `voters`,`archive`,`pwd`,`pwd_info`,`senior`,`single_parent`, `date_added`) VALUES (?,?,?,?,?,?,?,?,?)";
$stmt_residence_status = $con->prepare($sql_residence_status) or die ($con->error);
$stmt_residence_status->bind_param('sssssssss',$number,$add_status,$add_voters,$archive,$add_pwd,$add_pwd_check,$senior,$add_single_parent,$date_added);
$stmt_residence_status->execute();
$stmt_residence_status->close();

if(!empty($add_email_address) && !filter_var($add_email_address, FILTER_VALIDATE_EMAIL)){
  echo json_encode(array('status' => 'error', 'message' => 'Invalid email address'));
  exit;
}

$sql_add_user = "INSERT INTO `users`(`id`, `first_name`, `middle_name`, `last_name`, `username`, `password`, `user_type`, `contact_number`, `email`, `image`,`image_path`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

$username_to_use = $add_username;

// Check username uniqueness
$checkSql = "SELECT `id` FROM `users` WHERE `username` = ? LIMIT 1";
$checkStmt = $con->prepare($checkSql) or die($con->error);
$checkStmt->bind_param('s', $username_to_use);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
if($checkResult && $checkResult->num_rows > 0){
  echo json_encode(array('status' => 'error', 'message' => 'Username already exists'));
  exit;
}
$checkStmt->close();

$stmt_user = $con->prepare($sql_add_user) or die ($con->error);
$password_to_store = $posted_password !== '' ? $posted_password : $password;
$stmt_user->bind_param('sssssssssss',$number,$add_first_name,$add_middle_name,$add_last_name,$username_to_use,$password_to_store,$user_type,$add_contact_number,$add_email_address,$new_image_name,$new_image_path);
$stmt_user->execute();
$stmt_user->close();



$date_activity = $now = date("j-n-Y g:i A");  
  $admin = strtoupper('ADMIN').':' .' '. 'ADDED RESIDENT -'.' ' .$number.' |' .'  '.$add_first_name .' '. $add_last_name .' '. $add_suffix;
  $status_activity_log = 'create';


  $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
  $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
  $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
  $stmt_activity_log->execute();
  $stmt_activity_log->close();

  $resp = array(
    'status' => 'ok',
    'username' => $username_to_use,
    'password' => $password_to_store
  );
  echo json_encode($resp);
  




}catch(Exception $e){
  echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}



?>