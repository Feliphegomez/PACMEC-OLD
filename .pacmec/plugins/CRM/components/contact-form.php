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
                    <div class="alert alert-success" id="success-contact-alert">
                        <i class="fa fa-check-circle" id="success-contact-icon"></i> <p id="success-contact-message"></p>
                    </div>
                </div>
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


              <?php
              /* OPTION 2
                $menu = \pacmec_load_menu('contact_quick_link');
                if($menu !== false){
                  $r_html = "";
                  foreach ($menu->items as $key => $item1) {
                    // $item1->class = [$item1->title."-color"];
                    // $r_html .= pacmec_menu_item_to_li($item1, '_blank', false, false, false);
                    ?>
                    <div class="col-md-4">
                        <div class="content-box small left no-margin-lg" href="<?= ($item1->tag_href); ?>">
                            <div class="icon-shape-disable">
                                <i class="<?= $item1->icon; ?>" aria-hidden="true"></i>
                            </div>
                            <!--//<h5 class="text-color">Phone and Fax</h5>-->
                            <p>
                                <?= _autoT($item1->title); ?>
                            </p>
                        </div>
                    </div>

                    <?php
                  }
                  echo \PHPStrap\Util\Html::tag("ul", $r_html, ['footer-social social-icon pull-right'], []);
                }
                */
                ?>
          </div>
          <div class="col-md-12">
            <ul class="grid-md-3 grid-sm-2 no-wrap">
              <?php
              /* OPTION 3
                $menu = \pacmec_load_menu('contact_quick_link');
                if($menu !== false){
                  $r_html = "";
                  foreach ($menu->items as $key => $item1) {
                    // $item1->class = [$item1->title."-color"];
                    // $r_html .= pacmec_menu_item_to_li($item1, '_blank', false, false, false);
                    ?>
                        <li>
                          <a class="content-box left small" href="<?= ($item1->tag_href); ?>">
                              <div class="icon-shape-disable">
                                  <i class="<?= $item1->icon; ?>"></i>
                              </div>
                              <h5><?= _autoT($item1->title); ?></h5>
                              <!-- <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p> -->
                          </a>
                      </li>
                    <?php
                  }
                  echo \PHPStrap\Util\Html::tag("ul", $r_html, ['footer-social social-icon pull-right'], []);
                }
                */
                ?>
              </ul>
          </div>
        </div>

    </div>
</section>

<div class="section no-padding">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="map" class="contact-map"></div>
            </div>
        </div>
    </div>
</div>

<script>
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
  						$('#name, #subject, #email, #phone, #message').val("","","","","");
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

  	$("#name, #subject, #email, #phone, #message").focus(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	}).blur(function() {
  		$(this).parent('.form-group').removeClass('has-danger');
  	});
});
/*
* MAP
*/
Þ(document).ready(function () {
  var map = new GMaps({
      el: '#map',
      lat: 6.2400607,
      lng: -75.6019765,
      zoom: 18,
      zoomControl: true,
      zoomControlOpt: {
          style: 'SMALL',
          position: 'TOP_LEFT'
      },
      panControl: false,
      streetViewControl: false,
      mapTypeControl: false,
      overviewMapControl: false,
      scrollwheel: false
  });
  map.addMarker({
    lat: 6.2400607,
    lng: -75.6019765,
    icon: "<?= infosite('ga_map_marker'); ?>"
  });
  var styles = [{
    featureType: "road",
    elementType: "geometry",
    stylers: [{ lightness: 100 }, { visibility: "simplified" }]
  }, {
    featureType: "road",
    elementType: "labels",
    stylers: [{visibility: "off"}]
  }];
  map.addStyle({
      styledMapName: "Styled Map",
      styles: styles,
      mapTypeId: "map_style"
  });
  map.setStyle("map_style");
});
</script>
