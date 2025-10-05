<?php
session_start();
include_once '../connection.php';
require_once '../includes/send_otp.php';

// Clean output buffer
ob_clean();
header('Content-Type: application/json');

// Check if user has pending password otp reset in database
if (!isset($_SESSION['forgot_password_user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please start again.'
    ]);
    exit();
}

// Get OTP from request
if (!isset($_POST['otp']) || empty($_POST['otp'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter the OTP code.'
    ]);
    exit();
}

$user_id = $_SESSION['forgot_password_user_id'];
$otp = $_POST['otp'];

// Verify OTP
$result = verifyOTP($con, $user_id, $otp);

if ($result['success']) {
    // If OTP verified, allow password reset
    $_SESSION['otp_verified_for_reset'] = true;
    
    echo json_encode([
        'success' => true,
        'message' => 'OTP verified successfully'
    ]);
} else {
    echo json_encode($result);
}

exit();
?>
