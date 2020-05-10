<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require_once ('PHPMailer.php');
require_once ('SMTP.php');
require_once ('Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_mail($email, $message){
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = GMailUSER;                     // SMTP username
    $mail->Password   = GMailPWD;                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('todo@list.com', 'Todo List');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(false);                                  // Set email format to HTML
    $mail->Subject = 'Invitation for a todo list';
    $mail->Body    = $message;

    $mail->send();

}

