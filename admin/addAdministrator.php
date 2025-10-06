<?php 

include_once '../connection.php';
session_start();
// include OTP helper to send verification when email is provided
require_once __DIR__ . '/../includes/send_otp.php';




try{

  // sanitize inputs
  $first_name = isset($_POST['first_name']) ? $con->real_escape_string($_POST['first_name']) : '';
  $middle_name = isset($_POST['middle_name']) ? $con->real_escape_string($_POST['middle_name']) : '';
  $last_name = isset($_POST['last_name']) ? $con->real_escape_string($_POST['last_name']) : '';
  $username = isset($_POST['username']) ? $con->real_escape_string($_POST['username']) : '';
  $password = isset($_POST['password']) ? $con->real_escape_string($_POST['password']) : '';
  $contact_number = isset($_POST['contact_number']) ? $con->real_escape_string($_POST['contact_number']) : '';
  $email = isset($_POST['email']) ? $con->real_escape_string($_POST['email']) : '';
  $image = isset($_FILES['image']['name']) ? $con->real_escape_string($_FILES['image']['name']) : '';

  // Prepare a JSON response helper
  header('Content-Type: application/json');
  $response = ['status' => 'error', 'code' => '', 'message' => ''];

  // Basic required validations
  if (empty($first_name) || strlen($first_name) < 2) {
    $response['code'] = 'firstNameInvalid';
    $response['message'] = 'First name is required and must be at least 2 characters.';
    echo json_encode($response);
    exit;
  }
  if (empty($last_name) || strlen($last_name) < 2) {
    $response['code'] = 'lastNameInvalid';
    $response['message'] = 'Last name is required and must be at least 2 characters.';
    echo json_encode($response);
    exit;
  }
  if (empty($username) || strlen($username) < 6) {
    $response['code'] = 'usernameInvalid';
    $response['message'] = 'Username is required and must be at least 6 characters.';
    echo json_encode($response);
    exit;
  }
  if (empty($password) || strlen($password) < 6) {
    $response['code'] = 'passwordInvalid';
    $response['message'] = 'Password is required and must be at least 6 characters.';
    echo json_encode($response);
    exit;
  }

  // image handling
  date_default_timezone_set('Asia/Manila');
  $date = new DateTime();
  $uniqid = hexdec(uniqid()).$date->format("mdYHisv");
  $id = $uniqid;
  $user_type = 'secretary';

  if(!empty($image)){
    $type = explode('.',$image);
    $type = $type[count($type) -1];
    $new_image_name = uniqid(rand()) .'.'. $type;
    $new_image_path = '../assets/dist/img/' . $new_image_name;
    move_uploaded_file($_FILES['image']['tmp_name'],$new_image_path);
  }else{
    $new_image_name = '';
    $new_image_path = '';
  }

  // check username uniqueness
  $sql_check_username = "SELECT username FROM users WHERE username = ?";
  $stmt_check_username = $con->prepare($sql_check_username) or die ($con->error);
  $stmt_check_username->bind_param('s',$username);
  $stmt_check_username->execute();
  $stmt_check_username->store_result();
  $count_check = $stmt_check_username->num_rows;
  $stmt_check_username->close();
  if($count_check > 0){
    $response['code'] = 'usernameExists';
    $response['message'] = 'Username already exists.';
    echo json_encode($response);
    exit;
  }

  // Server-side email validation: allow empty, but if provided must be valid
  if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
    $response['code'] = 'emailInvalid';
    $response['message'] = 'Invalid email address.';
    echo json_encode($response);
    exit;
  }

  // If email provided, ensure it's not already used by another user
  if(!empty($email)){
    $sql_check_email = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt_check_email = $con->prepare($sql_check_email) or die($con->error);
    $stmt_check_email->bind_param('s',$email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();
    $count_email = $stmt_check_email->num_rows;
    $stmt_check_email->close();
    if($count_email > 0){
      $response['code'] = 'emailExists';
      $response['message'] = 'Email already in use.';
      echo json_encode($response);
      exit;
    }
  }

  // Insert user
  $sql = "INSERT INTO `users` (`id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`user_type`,`contact_number`,`email`,`image`,`image_path`)VALUES(?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->bind_param('sssssssssss',
    $id,
    $first_name,
    $middle_name,
    $last_name,
    $username,
    $password,
    $user_type,
    $contact_number,
    $email,
    $new_image_name,
    $new_image_path
  );
  $stmt->execute();
  $stmt->close();

  $date_activity = $now = date("j-n-Y g:i A");  
  $admin = strtoupper('ADMIN').':' .' '. 'ADDED ADMINISTRATOR  - '.' ' .$id.' | ' . $first_name .' '. $last_name;
  $status_activity_log = 'delete';
  $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
  $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
  $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
  $stmt_activity_log->execute();
  $stmt_activity_log->close();

  // If email was provided, send OTP and require verification before login
  if (!empty($email)) {
    // set pending verification session for this new user
    $_SESSION['pending_verification_user_id'] = $id;
    $_SESSION['pending_verification_email'] = $email;

    // Attempt to send OTP email (sendOTPEmail returns ['success'=>bool, 'message'=>...])
    $otp_result = sendOTPEmail($id, $email, $first_name, $last_name);
    if ($otp_result && isset($otp_result['success']) && $otp_result['success']) {
      // success - signal to frontend that verification is pending
      $response = ['status' => 'ok', 'verify' => true, 'message' => 'User created; verification pending.'];
      echo json_encode($response);
      exit;
    } else {
      // OTP send failed, but user was created - still return ok and log the failure
      error_log('Failed to send OTP for new admin: ' . ($otp_result['message'] ?? 'unknown'));
      $response = ['status' => 'ok', 'verify' => false, 'message' => 'User created but failed to send verification email.'];
      echo json_encode($response);
      exit;
    }
  }

  // No email provided, just return ok
  $response = ['status' => 'ok', 'verify' => false, 'message' => 'User created'];
  echo json_encode($response);


}catch(Exception $e){
  header('Content-Type: application/json');
  $err = ['status'=>'error','code'=>'exception','message'=>$e->getMessage()];
  echo json_encode($err);
}




?>