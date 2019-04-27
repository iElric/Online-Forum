<?PHP
//邮件发送
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_mail($mail_name, $user_name)
{

    require 'src/Exception.php';
    require 'src/PHPMailer.php';
    require 'src/SMTP.php';
    date_default_timezone_set('USA');

    $mail = new PHPMailer();

//close debug model default
    $mail->SMTPDebug = 0;

//use smtp method to send email
//according to http://phpmailer.github.io/PHPMailer/
    $mail->isSMTP();

//smtp must be true
    $mail->SMTPAuth = true;

//connect to google's server
    $mail->Host = 'smtp.gmail.com';

//use ssl to login
    $mail->SMTPSecure = 'ssl';


    $mail->Port = 465;


    $mail->Hostname = 'localhost';


    $mail->CharSet = 'UTF-8';

    $mail->FromName = 'AlgoPlayers';

    $mail->Username = 'playersforumm@gmail.com';

    $mail->Password = 'blacksheepwall';


    $mail->From = 'playersforumm@gmail.com';

    $mail->isHTML(true);


    $mail->addAddress($mail_name, 'xxx');

    $mail->Subject = 'THANK YOU FOR YOUR REGISTRATION!';


    $mail->Body = "You successfully registered for AlgoPlayer forum, now you can enjoy your computer science trip,
                    you may find many helpful messages from our forum, if you have any questions feel free to send 
                    e-mail to player@algoplayer.com";

    $mail->addAttachment('./src/20151002.png', 'test.png');


    $status = $mail->send();

}