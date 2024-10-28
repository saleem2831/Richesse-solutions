<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // Exit the script for OPTIONS requests
}

// Load Composer's autoloader
require 'vendor/autoload.php'; // Adjust this path if necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullName = $_POST['fullName']; // Changed to match the form input
    $email = $_POST['emailAddress']; // Changed to match the form input
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP();                                          // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = 'anwaarahmed0212@gmail.com';             // Your SMTP username (your email)
        $mail->Password   = 'ansari@02127860';                      // Your SMTP password
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                // TCP port to connect to

        // Recipients
        $mail->setFrom('anwaarahmed0212@gmail.com', 'Mailer');      // Set sender's email
        $mail->addAddress($email, $fullName);                  // Add a recipient

        // Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $subject;                              // Email subject
        $mail->Body    = nl2br($message);                      // Email body

        // Send email
        if ($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'Message has been sent successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Message could not be sent.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]); // Return error
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
