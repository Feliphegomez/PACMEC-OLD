<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */
// echo $title;
$r_message = null;
?>
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="heading-title">
                    <h3><?= $title; ?></h3>
                </div>
                <p>
                    <?= $content; ?>
                </p>
            </div>
            <div class="col-lg-8 col-md-12">

              <div id="message"><?php if($r_message !== null && !empty($r_message)) echo $r_message; ?></div>
							<form method="POST" class="contact-form" action="<?= infosite('siteurl')."/pacmec-form-contact"; ?>" name="pacemc-contactform" id="pacemc-contactform">
                <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                          <input type="text" name="name" id="name" class="form-control" placeholder="<?= _autoT('form_contact_name_placeholder'); ?>">
                      </div>
                      <div class="form-group">
                          <input type="email" name="email" id="email" class="form-control" placeholder="<?= _autoT('form_contact_email_placeholder'); ?>">
                      </div>
                      <div class="form-group">
                          <input type="text" name="phone" id="phone" class="form-control" placeholder="<?= _autoT('form_contact_phone_placeholder'); ?>">
                      </div>
                      <div class="form-group no-margin-lg">
                          <input type="text" name="subject" id="subject" class="form-control" placeholder="<?= _autoT('form_contact_subject_placeholder'); ?>">
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                          <textarea cols="40" rows="3" name="message" id="message" class="form-control" placeholder="<?= _autoT('form_contact_message_placeholder'); ?>"></textarea>
                      </div>
                      <button type="submit" id="buttonsend" class="btn btn-default btn-block"><?= _autoT('send_message'); ?></button>
                  </div>
                </div>
                <span class="loading"></span>
                <div class="success-contact">
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> Your <strong>Email Send</strong> Thank you.
                    </div>
                </div>

								<button class="btn float-btn color2-bg" id="submit"><?= _autoT('form_contact_btn_text'); ?><i class="fal fa-paper-plane"></i></button>
							</form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr class="separator">
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-building-o" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color">Office Address</h5>
                    <p>
                        2158 Madison Avenue <br>
                        Montgomery, AL(Alabama) 36107
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color">Office Hours</h5>
                    <p>
                        Monday to Friday : 7:00 - 18:00 <br>
                        Saturday : 9:00 - 15:00
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color">Phone and Fax</h5>
                    <p>
                        Phone : (111) 234 5678 <br>
                        Fax : (111) 432 5678
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
jQuery(document).ready(function($) {
});

window.addEventListener('load', function(){
  	var focusColor = "#169fe6";
  	var labelError = { "color" : "#fff", "background" :"#ff4444" };
  	var formError = { "border-color" : "#ff4444" };
  	var contactForm = $('.contact-form');
  	$('#buttonsend').on( 'click', function(e) {
  		e.preventDefault();
  		var name    = $('.contact-form #name').val();
  		var subject = $('.contact-form #subject').val();
  		var email   = $('.contact-form #email').val();
  		var phone   = $('.contact-form #phone').val();
  		var message = $('.contact-form #message').val();

  		$('.loading').css('display','inline-block').fadeIn('slow');
  		if (name != "" && subject != "" && email != "" && message != "" && phone != "") {
  			$.ajax({
  				url: '<?= infosite('siteurl')."/pacmec-form-contact"; ?>',
  				type: 'POST',
  				data: "name=" + name + "&subject=" + subject + "&email=" + email + "&phone=" + phone + "&message=" + message,
  				success: function(result) {
  					$('.loading').fadeOut('fast');
  					console.log(result);
  					if(result == "email_error") {
  						$('#email').css(formError).next('.require').text(' !');
  						$('label[for="email"]').css(labelError);
  					} else {
  						$('#name, #subject, #email, #message').val("","Name","Subject","Email","Message");
  						$('.success-contact').fadeIn();
  						$('.success-contact').fadeOut(5000, function(){ $(this).remove(); });
  					}
  				}
  			});
  			return false;
  		} else {
  			$('.loading').fadeOut('slow');
  			if(name <= 0) {
  				contactForm.find("#name").parent('.form-group').find('input').addClass('is-invalid');
  			} else {
  				contactForm.find("#name").parent('.form-group').find('input').removeClass('is-invalid');
  			}

  			if(subject <= 0) {
  				contactForm.find("#subject").parent('.form-group').find('input').addClass('is-invalid');
  			} else {
  				contactForm.find("#subject").parent('.form-group').find('input').removeClass('is-invalid');
  			}

  			if(email <= 0) {
  				contactForm.find("#email").parent('.form-group').find('input').addClass('is-invalid');
  			} else {
  				contactForm.find("#email").parent('.form-group').find('input').removeClass('is-invalid');
  			}

  			if(phone <= 0) {
  				contactForm.find("#phone").parent('.form-group').find('input').addClass('is-invalid');
  			} else {
  				contactForm.find("#phone").parent('.form-group').find('input').removeClass('is-invalid');
  			}

  			if(message <= 0) {
  				contactForm.find("#message").parent('.form-group').find('textarea').addClass('is-invalid');
  			} else {
  				contactForm.find("#message").parent('.form-group').find('textarea').removeClass('is-invalid');
  			}

  			return false;
  		}
  	});

  	$("#name, #subject, #email, #message").focus(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	}).blur(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	});
});
</script>
