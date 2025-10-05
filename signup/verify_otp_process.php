<?php
session_start();
include_once '../connection.php';
require_once '../includes/send_otp.php';

// Clean output buffer
ob_clean();
header('Content-Type: application/json');

// Check if user has pending verification
if (!isset($_SESSION['pending_verification_user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please register again.'
    ]);
    exit();
}

// Get OTP from POST req
if (!isset($_POST['otp']) || empty($_POST['otp'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter the OTP code.'
    ]);
    exit();
}

$user_id = $_SESSION['pending_verification_user_id'];
$otp = $_POST['otp'];

// Verify OTP
$result = verifyOTP($con, $user_id, $otp);

if ($result['success']) {
    // Clear session
    unset($_SESSION['pending_verification_user_id']);
    unset($_SESSION['pending_verification_email']);
}

echo json_encode($result);
exit();
?>
