<?php
session_start();
include_once '../connection.php';
require_once '../includes/send_otp.php';

header('Content-Type: application/json');

// Check if user has pending verification
if (!isset($_SESSION['pending_verification_user_id']) || !isset($_SESSION['pending_verification_email'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please register again.'
    ]);
    exit();
}

$user_id = $_SESSION['pending_verification_user_id'];
$email = $_SESSION['pending_verification_email'];

// Get user information
$sql = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
    exit();
}

$row = $result->fetch_assoc();
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$stmt->close();

// Send new OTP
$otp_result = sendOTPEmail($user_id, $email, $first_name, $last_name);

echo json_encode($otp_result);
?>
