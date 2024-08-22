<?php


session_start();

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
        $nom = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $message = " Nom : " . $nom . "\n" . " Email : " . $email . "\n" . " message : " . $message;

        require './vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vincdubois14@gmail.com';
            $mail->Password   = 'cwmw dupr lrtb rdyk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('from@example.com', 'GeoCaen');
            $mail->addAddress('vincdubois14@gmail.com');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = $message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                $_SESSION['message'] = "Message bien envoyé ! <br> Nous vous répondrons dans les meilleurs délais.";
                header("Location: ./contact.php");
                exit();
            } else {
                $_SESSION['message'] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
                header("Location: ./contact.php");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
            header("Location: ./contact.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Tous les champs du formulaire doivent être remplis.";
        header("Location: ./contact.php");

        exit();
    }
}
