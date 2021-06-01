<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PACMEC\System;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailsTemplates extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME                = 'emails_templates';

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(isset($opts->template_id)) $this->get_by_id($opts->template_id);
    if(isset($opts->template_slug)) $this->get_by('slug', $opts->template_slug);
  }

  public function __toString() : String
  {
    return $this->name;
  }

  public function set_all($obj)
  {
    global $PACMEC;
    $obj = (object) $obj;
    Parent::set_all($obj);
    if($this->isValid()){
    }
  }

  public function set_autot($tags_in,$tags_out)
  {
    $this->body_html = \str_replace($tags_in, $tags_out, $this->body_html);
  }

  public function send($subject, $to_email, $to_name, $email_contact_from=null)
  {
    $email_contact_from = ($email_contact_from==null) ? infosite('email_contact_from') : $email_contact_from;
    $PHPMailer = new PHPMailer(true);
    //$PHPMailer->SMTPDebug = 2;                              // Enable verbose debug output
    if(\infosite('smtp_enabled'))
    {
      $PHPMailer->isSMTP();                                   // Set mailer to use SMTP
      $PHPMailer->Host       = \infosite('smtp_host');        // Specify main and backup SMTP servers
      $PHPMailer->SMTPAuth   = \infosite('smtp_auth');        // Enable SMTP authentication
      $PHPMailer->Username   = \infosite('smtp_user');        // SMTP username
      $PHPMailer->Password   = \infosite('smtp_hash');        // SMTP password
      $PHPMailer->SMTPSecure = \infosite('smtp_secure');      // Enable TLS encryption, `ssl` also accepted
      $PHPMailer->Port       = \infosite('smtp_port');        // TCP port to connect to
    }
    $PHPMailer->CharSet    = infosite('charset');
    //Recipients
    $PHPMailer->setFrom($email_contact_from, infosite('sitename'));
    $PHPMailer->addAddress($to_email, $to_name);              // Add a recipient Name is optional (, 'name')
    // $PHPMailer->addReplyTo($email_contact_from, $e_subject);
    if(SMTP_CC!==false) $PHPMailer->addCC(SMTP_CC);
    if(SMTP_BCC!==false) $PHPMailer->addBCC(SMTP_BCC);
    // Content
    $PHPMailer->isHTML(true);                                  // Set email format to HTML
    $PHPMailer->Subject = $subject;
    $PHPMailer->Body    = ($this->body_html);
    $PHPMailer->AltBody = \strip_tags($this->body_html);
    return $PHPMailer->send();
  }
}
