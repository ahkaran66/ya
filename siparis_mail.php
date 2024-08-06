<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmail/src/Exception.php';
require 'phpmail/src/PHPMailer.php';
require 'phpmail/src/SMTP.php';

if (isset($_POST['siparis_mail'])) { // Değişiklik burada
    $musteriEmail = $_POST['siparis_mail']; // Değişiklik burada

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.kremkonopny.site';  // Sunucunuzun SMTP adresini buraya girin
        $mail->SMTPAuth = true;
        $mail->Username = 'test@kremkonopny.site';  // SMTP kullanıcı adınızı buraya girin
        $mail->Password = 'EnisHalil1.!';  // SMTP şifrenizi buraya girin
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // TLS kullanın
        $mail->Port = 587;  // Sunucunuzun SMTP portunu buraya girin

        $mail->setFrom('test@kremkonopny.site', 'test deneme');
        $mail->addAddress($musteriEmail);
        $mail->Subject = 'Siparişiniz Alındı';
        $mail->Body = 'Sayın Müşteri, Siparişiniz alınmıştır. Teşekkür ederiz.';

        $mail->send();
        echo 'E-posta gönderildi.';
    } catch (Exception $e) {
        echo 'E-posta gönderilemedi. Hata: ', $mail->ErrorInfo;
    }
} else {
    echo 'E-posta adresi alınamadı.';
}
?>