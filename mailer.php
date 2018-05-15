<?php
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $details = trim(filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS));
    $category = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));
    $genre = trim(filter_input(INPUT_POST, "genre", FILTER_SANITIZE_STRING));
    $format = trim(filter_input(INPUT_POST, "format", FILTER_SANITIZE_STRING));
    $year = trim(filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT));

    if ($name == "" || $email == "" || $category == "" || $title == "") {
        $error_msg = "Please fill in the required details";
    }
    if (!isset($error_msg) && $_POST["add"] != "") {
        $error_msg = "Bad form input";
    }
    if (!isset($error_msg) && !PHPMailer::validateAddress($email)) {
        $error_msg = "Invalid email";
    }

    if (!isset($error_msg)) {
        $email_body = "";
        $email_body .= "Name: " . $name . "\n";
        $email_body .= "Email: " . $email . "\n";
        $email_body .= "\n\nSuggested Item\n\n";
        $email_body .= "Category: " . $category . "\n";
        $email_body .= "Title: " . $title . "\n";
        $email_body .= "Format: " . $format . "\n";
        $email_body .= "Genre: " . $genre . "\n";
        $email_body .= "Year: " . $year . "\n";
        $email_body .= "Details: " . $details . "\n";

        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
//Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;
//Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
// use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
        $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "zaidshakiloz@gmail.com";
//Password to use for SMTP authentication
        $mail->Password = "bdefupwuxrbxkaqf";

        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('contact@example.com', $name);
        $mail->addReplyTo($email, $name);
        $mail->addAddress('cocoberry@coconut.com', 'Coconut Exame');
        $mail->Subject = 'Library Suggestion from ' . $name;
        $mail->Body = $email_body;
        if ($mail->send()) {
            header("location:suggest.php?status=thanks");
            exit;
        }
        $error_msg = "Mailer Error: " . $mail->ErrorInfo;
    }
}
