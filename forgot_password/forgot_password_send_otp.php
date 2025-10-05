<?php
session_start();
include_once '../connection.php';
require_once '../includes/send_otp.php';

// Clean output buffer
ob_clean();
header('Content-Type: application/json');

// Check if user is not empty
if (!isset($_POST['username']) || empty($_POST['username'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter your username'
    ]);
    exit();
}

$username = $con->real_escape_string($_POST['username']);

// Check if user exists and email
$sql = "SELECT id, email, first_name, last_name FROM users WHERE username = ? AND email IS NOT NULL AND email != ''";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Username not found or no email associated with this account'
    ]);
    exit();
}

$user = $result->fetch_assoc();
$user_id = $user['id'];
$email = $user['email'];
$first_name = $user['first_name'];
$last_name = $user['last_name'];

// Send OTP for password reset
$otp_result = sendOTPEmail($user_id, $email, $first_name, $last_name, 'password_reset');

if ($otp_result['success']) {
    $_SESSION['forgot_password_user_id'] = $user_id;
    $_SESSION['forgot_password_email'] = $email;
    
    echo json_encode([
        'success' => true,
        'message' => 'OTP sent successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $otp_result['message']
    ]);
}

exit();
?>
