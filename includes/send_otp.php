<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/email_config.php';
require_once __DIR__ . '/../connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateOTP($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9);
    }
    return $otp;
}

function saveOTPToDatabase($con, $user_id, $email, $otp) {
    try {
        // Delete any existing OTP for that specific user
        $sql_delete = "DELETE FROM otp_verification WHERE user_id = ?";
        $stmt_delete = $con->prepare($sql_delete);
        $stmt_delete->bind_param('s', $user_id);
        $stmt_delete->execute();
        $stmt_delete->close();
        
        // Insert new OTP
        $created_at = date('Y-m-d H:i:s');
        $expires_at = date('Y-m-d H:i:s', strtotime('+' . OTP_EXPIRY_MINUTES . ' minutes'));
        
        $sql_insert = "INSERT INTO otp_verification (user_id, email, otp_code, created_at, expires_at, is_verified) VALUES (?, ?, ?, ?, ?, 0)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param('sssss', $user_id, $email, $otp, $created_at, $expires_at);
        $result = $stmt_insert->execute();
        $stmt_insert->close();
        
        return $result;
    } catch (Exception $e) {
        error_log("Error saving OTP: " . $e->getMessage());
        return false;
    }
}

function sendOTPEmail($user_id, $email, $first_name = '', $last_name = '', $type = 'registration') {
    global $con;
    
    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Invalid email address'
        ];
    }
    
    // Generate OTP
    $otp = generateOTP(OTP_LENGTH);
    
    // Save OTP to database
    if (!saveOTPToDatabase($con, $user_id, $email, $otp)) {
        return [
            'success' => false,
            'message' => 'Failed to save OTP'
        ];
    }
    
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;
        
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($email, $first_name . ' ' . $last_name);
        
        $full_name = trim($first_name . ' ' . $last_name);
        
        if ($type === 'password_reset') {
            $mail->Subject = 'Password Reset OTP - Barangay Hulo';
            $email_title = 'Password Reset';
            $email_intro = 'You have requested to reset your password. To proceed, please use the One-Time Password (OTP) below:';
        } else {
            $mail->Subject = OTP_EMAIL_SUBJECT;
            $email_title = 'Account Verification';
            $email_intro = 'Thank you for registering with Barangay Hulo System. To complete your registration, please verify your email address using the One-Time Password (OTP) below:';
        }
        
        $mail->isHTML(true);
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #b30000 0%, #8b0000 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .otp-box { background: white; border: 2px dashed #b30000; padding: 20px; margin: 20px 0; text-align: center; border-radius: 5px; }
                .otp-code { font-size: 32px; font-weight: bold; color: #b30000; letter-spacing: 5px; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Barangay Hulo</h1>
                    <p>' . $email_title . '</p>
                </div>
                <div class="content">
                    <p>Hello ' . htmlspecialchars($full_name) . ',</p>
                    <p>' . $email_intro . '</p>
                    
                    <div class="otp-box">
                        <p style="margin: 0; font-size: 14px; color: #666;">Your OTP Code:</p>
                        <div class="otp-code">' . $otp . '</div>
                        <p style="margin: 10px 0 0 0; font-size: 12px; color: #999;">This code expires in ' . OTP_EXPIRY_MINUTES . ' minutes</p>
                    </div>
                    
                    <div class="warning">
                        <strong>⚠️ Security Notice:</strong>
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            <li>Never share this code with anyone</li>
                            <li>Barangay Hulo staff will never ask for your OTP</li>
                            <li>If you did not request this code, please ignore this email</li>
                        </ul>
                    </div>
                    
                    <p>If you have any questions, please contact the Barangay Hulo office.</p>
                    
                    <p>Best regards,<br><strong>Barangay Hulo System</strong></p>
                </div>
                <div class="footer">
                    <p>91 Coronado, Barangay Hulo, Mandaluyong, Philippines</p>
                    <p>&copy; ' . date('Y') . ' Barangay Hulo. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $mail->AltBody = "Hello $full_name,\n\n"
                       . "Your OTP verification code is: $otp\n\n"
                       . "This code expires in " . OTP_EXPIRY_MINUTES . " minutes.\n\n"
                       . "If you did not request this code, please ignore this email.\n\n"
                       . "Best regards,\nBarangay Hulo System";
        
        // Send email
        $mail->send();
        
        return [
            'success' => true,
            'message' => 'OTP sent successfully to ' . $email,
            'otp' => $otp // For testing purposes only - REMOVE IN PRODUCT !!!!!
        ];
        
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return [
            'success' => false,
            'message' => 'Failed to send email: ' . $mail->ErrorInfo
        ];
    }
}

function verifyOTP($con, $user_id, $otp) {
    try {
        $sql = "SELECT * FROM otp_verification WHERE user_id = ? AND otp_code = ? AND is_verified = 0 ORDER BY created_at DESC LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss', $user_id, $otp);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'message' => 'Invalid OTP code'
            ];
        }
        
        $row = $result->fetch_assoc();
        $stmt->close();
        
        // Check if OTP has expired
        $expires_at = strtotime($row['expires_at']);
        $current_time = time();
        
        if ($current_time > $expires_at) {
            return [
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ];
        }
        
        // Change OTP to veried
        $sql_update_otp = "UPDATE otp_verification SET is_verified = 1 WHERE id = ?";
        $stmt_update_otp = $con->prepare($sql_update_otp);
        $stmt_update_otp->bind_param('i', $row['id']);
        $stmt_update_otp->execute();
        $stmt_update_otp->close();
        
        // Change OTP to veried
        $sql_update_user = "UPDATE users SET is_verified = 1 WHERE id = ?";
        $stmt_update_user = $con->prepare($sql_update_user);
        $stmt_update_user->bind_param('s', $user_id);
        $stmt_update_user->execute();
        $stmt_update_user->close();
        
        return [
            'success' => true,
            'message' => 'Email verified successfully!'
        ];
        
    } catch (Exception $e) {
        error_log("Error verifying OTP: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Verification failed. Please try again.'
        ];
    }
}

?>
