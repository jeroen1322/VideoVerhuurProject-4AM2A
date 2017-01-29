<?php
// require 'PHPMailerAutoload.php';

function blockMail($naam, $email){
  $mail = new PHPMailer;

  // $mail->SMTPDebug = 3;                               // Enable verbose debug output

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'mbotempovideo@gmail.com';                 // SMTP username
  $mail->Password = 'JeroenSara7!';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 587;                                    // TCP port to connect to

  $mail->setFrom('noreply@tempovideo.nl', 'Tempovideo');
  $mail->addAddress($email, 'Jeroen Grooten');     // Add a recipient

  $mail->isHTML(true);                                  // Set email format to HTML

  $mail->Subject = 'Uw account is geblokkeerd';
  $mail->Body    = 'Geachte ' . $naam . ', <br><br>
                    Uw <a href="http://tempovideo.nl">Tempovideo</a> account is geblokkeerd wegens het niet optijd inleveren van een
                    - door u gehuurde - film.
                    <br><br><br>
                    Hoogachtend,<br><br>
                    Tempovideo';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  // if(!$mail->send()) {
  //     echo 'ERROR TIJDENS HET VERSTUREN VAN DE EMAIL <br>';
  //     echo 'Mail Error: ' . $mail->ErrorInfo;
  // }
}

function confirmMail($naam, $email, $id){
  $mail = new PHPMailer;

  // $mail->SMTPDebug = 3;                               // Enable verbose debug output

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'mbotempovideo@gmail.com';                 // SMTP username
  $mail->Password = 'JeroenSara7!';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 587;                                    // TCP port to connect to

  $mail->setFrom('noreply@tempovideo.nl', 'Tempovideo');
  $mail->addAddress($email, 'Jeroen Grooten');     // Add a recipient

  $mail->isHTML(true);                                  // Set email format to HTML

  $mail->Subject = 'Registratie bevestiging';
  $mail->Body    = 'Geachte ' . $naam . ', <br><br>
                    Activeer uw <a href="http://tempovideo.nl/login?action=activate&code='.$id.'">TempoVideo</a> account.<br><br>
                    Hoogachtend,<br><br>
                    Tempovideo';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if(!$mail->send()) {
      echo 'ERROR TIJDENS HET VERSTUREN VAN DE EMAIL <br>';
      echo 'Mail Error: ' . $mail->ErrorInfo;
  }
}

function ophaalMail($order, $filmtitel, $naam, $email, $afleverdatum){
  $mail = new PHPMailer;

  // $mail->SMTPDebug = 3;                               // Enable verbose debug output

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'mbotempovideo@gmail.com';                 // SMTP username
  $mail->Password = 'JeroenSara7!';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 587;                                    // TCP port to connect to

  $mail->setFrom('noreply@tempovideo.nl', 'Tempovideo');
  $mail->addAddress($email, 'Jeroen Grooten');     // Add a recipient

  $mail->isHTML(true);                                  // Set email format to HTML

  $mail->Subject = 'Registratie bevestiging';
  $mail->Body    = 'Geachte ' . $naam . ', <br><br>
                    Uw bestelling nr '.$order.' (afgeleverd op '.$afleverdatum.') wordt morgen opgehaald door de Tempovideo Bezorger.<br>
                    Het betreft de film "'.$filmtitel.'".<br><br>

                    Met vriendelijke groet,<br>
                    TempoVideo

                    ';
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  if(!$mail->send()) {
      echo 'ERROR TIJDENS HET VERSTUREN VAN DE EMAIL <br>';
      echo 'Mail Error: ' . $mail->ErrorInfo;
  }else{
    return true;
  }
}
