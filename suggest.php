<?php
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(filter_input(INPUT_POST,"name",FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL));
    $details = trim(filter_input(INPUT_POST,"details",FILTER_SANITIZE_SPECIAL_CHARS));

    if ($name == "" || $email == "" || $details == ""){
        echo "Please fill in the required details";
        exit;
    }
    if ($_POST["add"] != ""){
        echo "Bad form input";
        exit;
    }
    if (!PHPMailer::validateAddress($email)){
        echo "Invalid email";
        exit;
    }

    $email_body = "";
    $email_body .= "Name " . $name . "\n";
    $email_body .= "Email " . $email . "\n";
    $email_body .= "Details " . $details . "\n";
    

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
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
    }
    header("location:suggest.php?status=thanks");
}

$pageTitle = "Suggest a media item";
$section = "suggest";

include "includes/header.php";?>

<div class="section page">
    <div class="wrapper">
        <h1>Suggest a media item</h1>
        <?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
            echo "<p>Thanks for the email!</p>";
        } else { ?>
        <p>Your suggestions are welcomed. Complete the form below!</p>
        <form method="post" action="suggest.php">
            <table>
                <tr>
                    <th> <label for="name">Name</label></th>
                    <td><input type="text" name="name" id="name"></td>
                </tr>

                <tr>
                    <th> <label for="email">Email</label></th>
                    <td><input type="text" name="email" id="email"></td>
                </tr>

                <tr>
                    <th> <label for="details">Suggest Item Details</label></th>
                    <td><textarea name="details" id="details"></textarea></td>
                </tr>

                <tr style="display:none;">
                    <th> <label for="add">ADDRESS</label></th>
                    <td><input type="text" name="add" id="add"><p>Leave this blank</p></td>
                </tr>
            </table>
            <input type="submit" value="Send">





        </form> <?php } ?>
    </div>

</div>




<?php include "includes/footer.php";?>