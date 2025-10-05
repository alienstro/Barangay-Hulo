<?php
session_start();
include_once '../connection.php';

// Clean output buffer
ob_clean();
header('Content-Type: application/json');

// Check if user has pending password otp reset in database
if (!isset($_SESSION['forgot_password_user_id']) || !isset($_SESSION['otp_verified_for_reset'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please start the password reset process again.'
    ]);
    exit();
}

// Get password from POST req
if (!isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please provide both passwords.'
    ]);
    exit();
}

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords
if (strlen($new_password) < 8) {
    echo json_encode([
        'success' => false,
        'message' => 'Password must be at least 8 characters long.'
    ]);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode([
        'success' => false,
        'message' => 'Passwords do not match.'
    ]);
    exit();
}

$user_id = $_SESSION['forgot_password_user_id'];

// Update password in database 
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('ss', $new_password, $user_id);

if ($stmt->execute()) {
    // Clear all session variables related to password reset
    unset($_SESSION['forgot_password_user_id']);
    unset($_SESSION['forgot_password_email']);
    unset($_SESSION['otp_verified_for_reset']);
    
    // Log the password reset activity
    $date_activity = date("j-n-Y g:i A");
    $admin = 'USER PASSWORD RESET - User ID: ' . $user_id;
    $status_activity_log = 'password_reset';

    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`) VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log);
    $stmt_activity_log->bind_param('sss', $admin, $date_activity, $status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();

    echo json_encode([
        'success' => true,
        'message' => 'Password reset successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update password. Please try again.'
    ]);
}

$stmt->close();
exit();
?>
