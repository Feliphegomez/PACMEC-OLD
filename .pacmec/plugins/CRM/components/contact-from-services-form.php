<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */
$subject_tmp = _autoT('visitor_new_adv_from')." ".$service->name;
?>
<section class="section no-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="heading-title gap" data-gap-top="85">
                    <h3><?= \PHPStrap\Util\Html::tag('i', '', [$service->icon])._autoT('visitor_new_adv_from'); ?> <strong class="text-color"><?= $service->name; ?></strong></h3>
                </div>
                <p><?= $service->description_short; ?></p>
                <div class="row">
                  <ul class="grid-md-2 grid-sm-1 no-wrap">
                    <?php foreach ($service->characteristics as $characteristic) : ?>
                      <li>
                          <div class="content-box left small">
                              <div class="icon-shape-disable">
                                  <i class="<?= $characteristic->icon; ?>"></i>
                              </div>
                              <h5><?= $characteristic->name; ?></h5>
                              <p><?= $characteristic->description_short; ?></p>
                          </div>
                      </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="estimate-form">
                    <div class="heading-title">
                        <h3><?= $title; ?> <strong class="text-color"><?= _autoT('free'); ?></strong></h3>
                    </div>
                    <form class="contact-form-services row" method="POST" action="<?= infosite('siteurl')."/pacmec-form-contact-services"; ?>" name="pacemc-contactform" id="pacemc-contactform">
                        <div class="col-md-6">
                          <div class="form-group">
                              <input type="text" name="name" id="name" class="form-control" placeholder="<?= _autoT('form_contact_name_placeholder'); ?>">
                          </div>
                          <div class="form-group">
                              <input type="email" name="email" id="email" class="form-control" placeholder="<?= _autoT('form_contact_email_placeholder'); ?>">
                          </div>
                          <div class="form-group">
                              <input type="text" name="phone" id="phone" class="form-control" placeholder="<?= _autoT('form_contact_phone_placeholder'); ?>">
                          </div>
                          <div class="form-group">
                              <input type="text" name="service_address" id="service_address" class="form-control" placeholder="<?= _autoT('form_contact_service_address_placeholder'); ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group no-margin-lg">
                                <select class="custom-select form-control" name="refer" id="refer">
                                  <option value=""><?= _autoT('form_contact_refer_placeholder'); ?></option>
                                  <option value="google">Google</option>
                                  <option value="instagram">Instagram</option>
                                  <option value="facebook">Facebook</option>
                                  <option value="recommended"><?= _autoT('recommended'); ?></option>
                                  <option value="habitissimo">Habitissimo</option>
                                  <option value="linkedin">Linkedin</option>
                                  <option value="advertisement"><?= _autoT('advertisement'); ?></option>
                                  <option value="other"><?= _autoT('other'); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input value="<?= $subject_tmp; ?>" type="hidden" name="subject" id="subject" class="form-control" placeholder="<?= _autoT('form_contact_subject_placeholder'); ?>">
                            </div>
                            <div class="form-group no-margin">
                                <textarea style="height:185px;" cols="40" rows="7" name="message" id="message" class="form-control" placeholder="<?= _autoT('form_contact_message_placeholder'); ?>"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr class="separator">
                            <div class="form-group no-margin">
                                <button type="submit" id="buttonsend" class="btn btn-default btn-block"><?= _autoT('send_message'); ?></button>
                            </div>
                        </div>
                    </form>

                    <span class="loading"></span>
                    <div class="success-contact">
                        <div class="alert alert-success" id="success-contact-alert">
                            <i class="fa fa-check-circle" id="success-contact-icon"></i> <p id="success-contact-message"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php
                //$service->description;
                 ?>
            </div>
            <div class="col-md-12">
                <hr class="separator">
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-building-o" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color"><?= _autoT('business_address'); ?></h5>
                    <p>
                        <?= infosite('business_address'); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color"><?= _autoT('business_office_hours'); ?></h5>
                    <p>
                        <?= infosite('business_office_hours'); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-box small left no-margin-lg">
                    <div class="icon-shape-disable">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                    <h5 class="text-color"><?= _autoT('business_info_contact_fast'); ?></h5>
                    <p>
                        <?= infosite('business_info_contact_fast'); ?>
                    </p>
                </div>
            </div>
          </div>
        </div>
    </div>
</section>

<section class="section">
  <div class="container">
    <div class="row">
  </div>
</section>
<script>
window.addEventListener('load', function(){
  	var focusColor = "#169fe6";
  	var labelError = { "color" : "#fff", "background" :"#ff4444" };
  	var formError = { "border-color" : "#ff4444" };
  	var contactForm = $('.contact-form-services');
  	$('#buttonsend').on( 'click', function(e) {
  		e.preventDefault();
  		var name    = $('.contact-form-services #name').val();
  		var subject = $('.contact-form-services #subject').val();
  		var email   = $('.contact-form-services #email').val();
  		var phone   = $('.contact-form-services #phone').val();
  		var message = $('.contact-form-services #message').val();
  		var service_address = $('.contact-form-services #service_address').val();
  		var refer = $('.contact-form-services #refer').val();

  		$('.loading').css('display','inline-block').fadeIn('slow');
  		if (name != "" && subject != "" && email != "" && message != "" && phone != "" && service_address != "" && refer != "") {
  			$.ajax({
  				url: '<?= infosite('siteurl')."/pacmec-form-contact-services"; ?>',
  				type: 'POST',
  				data: "name=" + name + "&subject=" + subject + "&email=" + email + "&phone=" + phone + "&message=" + message + "&service_address=" + service_address + "&refer=" + refer,
  				success: function(result) {
  					$('.loading').fadeOut('fast');
  					console.log(result);
  					if(result.error == true) {
              $('#success-contact-alert').attr('class', 'alert alert-danger');
              $('#success-contact-icon').attr('class', 'fa fa-times-circle');
              $('#error-contact-message').html(result.message);
              $('.success-contact').fadeIn();
  						$('.success-contact').fadeOut(5000, function(){ $(this).remove(); });
              /*
  						$('#email').css(formError).next('.require').text(' !');
  						$('label[for="email"]').css(labelError);*/
  					} else {
              $('#success-contact-alert').attr('class', 'alert alert-success');
              $('#success-contact-icon').attr('class', 'fa fa-check-circle');
              $('#success-contact-message').html(result.message);
  						$('#name, #subject, #email, #phone, #message, #service_address, #refer').val("","","","","","","");
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

  			if(service_address <= 0) {
  				contactForm.find("#service_address").parent('.form-group').find('input').addClass('is-invalid');
  			} else {
  				contactForm.find("#service_address").parent('.form-group').find('input').removeClass('is-invalid');
  			}

  			if(refer <= 0) {
  				contactForm.find("#refer").parent('.form-group').find('select').addClass('is-invalid');
  			} else {
  				contactForm.find("#refer").parent('.form-group').find('select').removeClass('is-invalid');
  			}

  			return false;
  		}
  	});

  	$("#name, #subject, #email, #phone, #message, #service_address, #refer").focus(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	}).blur(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	});
});
</script>
