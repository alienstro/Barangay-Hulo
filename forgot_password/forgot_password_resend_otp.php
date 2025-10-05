<?php
session_start();
include_once '../connection.php';
require_once '../includes/send_otp.php';

// Clean output buffer
ob_clean();
header('Content-Type: application/json');

// Check if user has pending password otp reset in database
if (!isset($_SESSION['forgot_password_user_id']) || !isset($_SESSION['forgot_password_email'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please start again.'
    ]);
    exit();
}

$user_id = $_SESSION['forgot_password_user_id'];
$email = $_SESSION['forgot_password_email'];

// Get user details
$sql = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit();
}

$user = $result->fetch_assoc();
$first_name = $user['first_name'];
$last_name = $user['last_name'];

// Send new OTP
$otp_result = sendOTPEmail($user_id, $email, $first_name, $last_name, 'password_reset');

echo json_encode($otp_result);
exit();
?>
