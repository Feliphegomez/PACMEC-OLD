<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$result_contact = (object) [
  "args"=>$args,
  "error"=>true,
  "response"=>null,
  "message"=>null
];
try {
  if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");
  if(!isset($name)||trim($name) == '') throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!isset($email)||trim($email) == '') throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!\isEmail($email)) throw new \Exception(_autoT("invalid_email"), 1);
  if(!isset($phone)) throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!isset($subject)) throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!isset($message)) throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!isset($service_address)) throw new \Exception(_autoT("invalid_parameters"), 1);
  if(!isset($refer)) throw new \Exception(_autoT("invalid_parameters"), 1);
  if(get_magic_quotes_gpc()) { $comments = stripslashes($comments); }
  // Configuration option.
  // Enter the email address that you want to emails to be sent to.
  // Example $address = "yourmail@pacmec.org";
  // $address = infosite('contact_email');
  $email_contact_from = infosite('email_contact_from');
  $email_contact_services_received = infosite('email_contact_services_received');
  // Configuration option.
  // i.e. The standard subject will appear as, "You've been contacted by John Doe."
  // Example, $e_subject = '$name . ' has contacted you via Your Website.';
  $e_subject = _autoT('staff_new_adv_from');
  $template_org = file_get_contents('templates/contact-adv.php', true);
  $tags_in = [
    '%sitelogo%',
    '%sitename%',
    '%PreviewText%',
    '%staff_new_adv_from%',
    '%staff_new_adv_text%',
    '%name%',
    '%email%',
    '%phone%',
    '%subject%',
    '%message%',
    '%siteurl%',
    '%service_address%',
    '%refer%',
  ];
  $tags_out = [
    infosite('sitelogo'),
    infosite('sitename'),
    infosite('sitedescr'),
    _autoT('staff_new_adv_from'),
    _autoT('staff_new_adv_text'),
    $name,
    $email,
    $phone,
    $subject,
    $message,
    infosite('siteurl').infosite('homeurl'),
    $service_address,
    $refer,
  ];
  $template = \str_replace($tags_in, $tags_out, $template_org);

  $mail = new PHPMailer(true);

  try {
      //Server settings
      //$mail->SMTPDebug = 2;                 // Enable verbose debug output
      $mail->isSMTP();                      // Set mailer to use SMTP
      $mail->Host       = SMTP_HOST;        // Specify main and backup SMTP servers
      $mail->SMTPAuth   = SMTP_AUTH;        // Enable SMTP authentication
      $mail->Username   = SMTP_USER;        // SMTP username
      $mail->Password   = SMTP_PASS;        // SMTP password
      $mail->SMTPSecure = SMTP_SECURE;      // Enable TLS encryption, `ssl` also accepted
      $mail->Port       = SMTP_PORT;        // TCP port to connect to
      $mail->CharSet    = infosite('charset');

      //Recipients
      $mail->setFrom($email_contact_from, infosite('sitename'));
      $mail->addAddress($email_contact_services_received);     // Add a recipient Name is optional (, 'name')
      $mail->addReplyTo($email, $e_subject);
      if(SMTP_CC!==false) $mail->addCC(SMTP_CC);
      if(SMTP_BCC!==false) $mail->addBCC(SMTP_BCC);

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = ($e_subject);
      $mail->Body    = ($template);
      $mail->AltBody = \strip_tags($template);
      $result_contact->error = !($mail->send());
      $result_contact->message = _autoT("form_contact_send_success");
  } catch (Exception $e) {
      throw new \Exception(_autoT("form_contact_send_fail"), 1);
      //throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 1);
  }

  /*
  PHP MAIL FUNCTION
  $headers = "From: $email_contact_from" . PHP_EOL;
  $headers .= "Reply-To: $email_contact_from" . PHP_EOL;
  $headers .= "MIME-Version: 1.0" . PHP_EOL;
  $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
  $headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

  if(mail($address_prop, $e_subject, $template, $headers)) {
    /*echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h3>Email Sent Successfully.</h3>";
    echo "<p>Thank you <strong>$name</strong>, your message has been submitted to us.</p>";
    echo "</div>";
    echo "</fieldset>";* /
    $result_contact->error = false;
  } else {
    throw new \Exception("error_send", 1);
  }*/
} catch (\Exception $e) {
  $result_contact->message = $e->getMessage();
}
echo json_encode($result_contact, JSON_PRETTY_PRINT);
