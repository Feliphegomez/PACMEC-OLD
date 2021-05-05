<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */

function pacmec_menu_item_to_li($item1, $classItem=[], $attsItem=[], $tree = true, $enable_tag = true, $enable_icon_m = true, $class_ul_child=[], $classSubitem=[], $attsSubitem=[])
{
  $icon = "";
  if(!empty($item1->icon)) $icon      = \PHPStrap\Util\Html::tag("i", "", [$item1->icon]);
  $icon_more = isset($item1->childs) && count($item1->childs)>0 ? \PHPStrap\Util\Html::tag("i", "", ["fa fa-caret-down"]) : "";

  $title = ($item1->title);

  $attrs_a = [];
  $attrs_a["href"] = $item1->tag_href;
  if(isset($attsItem['target'])) $attrs_a["target"] = $attsItem['target'];

  if($GLOBALS['PACMEC']['path'] === $item1->tag_href) $classItem[] = ' active';

  if(isset($item1->childs) && count($item1->childs)>0 && $tree == true){
    $subitems = "";
    foreach ($item1->childs as $subitem) {
      $subitems .= \pacmec_menu_item_to_li($subitem, $classSubitem, $attsSubitem, $tree, $enable_tag, $enable_icon_m, $class_ul_child);
    }
    $ul_more = "\n".\PHPStrap\Util\Html::tag("ul", $subitems, $class_ul_child);
  } else {
    $ul_more = "";
  }
  $link = \PHPStrap\Util\Html::tag("a", "\n{$icon}".($enable_tag==true?" {$title}":"").($enable_icon_m==true?" {$icon_more}":""), $classItem, $attrs_a);
  $item_html = \PHPStrap\Util\Html::tag("li", "{$link}{$ul_more}", $classItem, $attsItem);
  return $item_html;
}

/**
*
* Create UL Socials Icons
*
* @param array  $atts
* @param string  $content
*/
function pacmec_social_icons($atts, $content='')
{
  try {
    $repair = shortcode_atts([
      "target" => "_self",
      "enable_tag" => false,
      "class" => [],
      "iconpro" => "",
      "menu_slug" => false,
    ], $atts);
    if($repair['menu_slug'] == false){
      throw new \Exception("Menu no detectado.", 1);
    } else {
      $menu = pacmec_load_menu($repair['menu_slug']);
      if($menu !== false){
        $r_html = "";
        foreach ($menu->items as $key => $item1) {
          $r_html .= \pacmec_menu_item_to_li($item1, $repair['target'], false, $repair['enable_tag']);
        }
        $styles = isset($repair['class']) ? $repair['class'] : ["no-list-style"];
        return \PHPStrap\Util\Html::tag("ul", $r_html, $styles);
      } else {
      throw new \Exception("Menu no encontrado.", 1);
      }
    }
  } catch (\Exception $e) {
    return "Ups: pacmec_social_icons: " . $e->getMessage();
  }
}
add_shortcode('pacmec-social-icons', 'pacmec_social_icons');

function pacmec_captcha_widget_forms($atts=[], $content='')
{
  $args = \shortcode_atts([
    "id" => randString(11),
    "name" => randString(11),
    "theme" => 'custom-pacmec',
  ], $atts);
  // pacmec_captcha_widget_html
  //
  return \pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-".$args["id"], $args["name"], $args["theme"]);
}
add_shortcode('pacmec-captcha-widget-forms', 'pacmec_captcha_widget_forms');

function pacmec_form_signin($atts=[], $content='')
{
  /*
  <div class="pacmec-row">
    <div class="w3-col m4 l3">
      <p>12 columns on a small screen, 4 on a medium screen, and 3 on a large screen.</p>
    </div>
    <div class="w3-col m8 l9">
      <p>12 columns on a small screen, 8 on a medium screen, and 9 on a large screen.</p>
    </div>
  </div>*/
  global $PACMEC;
  $args = \shortcode_atts([
    'redirect' => false
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "signin-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'pacmec-row']);
  $form->setWidths(12,12);

  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(isset($PACMEC['fullData']["submit-{$form_slug}"]) && isset($PACMEC['fullData']['nick']) && isset($PACMEC['fullData']['hash'])){
            $r_login = $PACMEC['session']->login([
              'nick' => $PACMEC['fullData']['nick'],
              'hash' => $PACMEC['fullData']['hash']
            ]);
            switch ($r_login) {
              case 'no_exist':
                $form->setErrorMessage(__a('signin_r_no_exist'));
                return false;
                break;
              case 'inactive':
                $form->setErrorMessage(__a('signin_r_inactive'));
                return false;
                break;
              case 'error':
                $form->setErrorMessage(__a('signin_r_error'));
                return false;
                break;
              case 'success':
                $form->setSucessMessage(__a('signin_r_success'));
                $url = (isset($PACMEC['fullData']['redirect'])) ? ($PACMEC['fullData']['redirect']) : infosite('siteurl').__url_s("/%pacmec_meaccount%");
                echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
                return true;
                break;
              case 'invalid_credentials':
                $form->setErrorMessage(__a('signin_r_invalid_credentials'));
                return false;
                break;
              default:
                $form->setErrorMessage(__a('undefined'));
                return false;
                break;
            }
          } else {
            $form->setErrorMessage(__a('signin_r_invalid_info'));
            return false;
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);

  $form->hidden([
    [
      "name"  => "redirect",
      "value" => ($args['redirect']==false) ? infosite('siteurl').__url_s("/%pacmec_meaccount%") : urldecode($args['redirect'])
    ]
  ]);

  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('nick', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('username')
    , ''
    , ['pacmec-col m12 l12']
  );

  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('hash', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('hash')
    , ''
    , ['pacmec-col m12 l12']
  );

  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('signin'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'pacmec-button pacmec-green pacmec-round-large w-100'
  ]);

  $form->Code .= '
    <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
      <a href="'.infosite('siteurl')."/{$GLOBALS['PACMEC']['permanents_links']['%forgotten_password_slug%']}".'" class="forget-pwd mb-3">'.__a('meaccount_forgotten_password').'</a>
    </div>';
  return isGuest() ? \PHPStrap\Util\Html::tag('div', $form, ['pacmec-animate-zoom']) : '';
}
add_shortcode('pacmec-form-signin', 'pacmec_form_signin');

function pacmec_form_forgotten_password($atts=[], $content='')
{
  global $PACMEC;
  $args = \shortcode_atts([
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "password-forgotten-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'row']);
  $form->setWidths(12,12);
  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('Enlace invalido', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(
        isset($PACMEC['fullData']["ue"])
        && isset($PACMEC['fullData']["kr"])
      ){
        $key = $PACMEC['fullData']["kr"];
        $email = $PACMEC['fullData']["ue"];
        $check_rev = $PACMEC['session']->validateUserDB_recover($key, $email);
        switch ($check_rev) {
          case 'no_exist':
            #$form->setErrorMessage(__a('signin_r_no_exist'));
            return false;
            break;
          case 'inactive':
            #$form->setErrorMessage(__a('signin_r_inactive'));
            return false;
            break;
          case 'error':
            #$form->setErrorMessage(__a('signin_r_error'));
            return false;
            break;
          case $check_rev->id > 0:
            $PACMEC['session']->set('id', $check_rev->id, 'user');
            return true;
            #$form->setErrorMessage(__a('recover_password_r_fail'));
            return false;
            break;
          default:
            #$form->setErrorMessage(__a('recover_password_r_fail'));
            return false;
            break;
         }
      }
      return false;
    })
    , new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['pass1'])
            && isset($PACMEC['fullData']['pass2'])
            && isset($PACMEC['session']->id) && $PACMEC['session']->id > 0
          ){
            if($PACMEC['fullData']['pass1'] !== $PACMEC['fullData']['pass2']){
              $form->setErrorMessage(__a('change_pass_r_error_not_match'));
              return false;
            }
            if($PACMEC['session']->change_pass($PACMEC['fullData']['pass2']) == true){
              $form->setSucessMessage(__a('change_pass_r_success'));
              return true;
            } else {
              $form->setErrorMessage(__a('change_pass_r_error'));
              return false;
            }
          } else {
            $form->setErrorMessage(__a('recover_password_r_fail'));
            return false;
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  if(
    isset($PACMEC['fullData']["ue"])
    && isset($PACMEC['fullData']["kr"])
  ){

    $form->addFieldWithLabel(
      \PHPStrap\Form\Password::withNameAndValue('pass1', '', 254, [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\MinLengthValidation(4)
      ])
      , __a('pass1')
      , ''
      , ['col-lg-12']
    );
      $form->addFieldWithLabel(
        \PHPStrap\Form\Password::withNameAndValue('pass2', '', 254, [
          new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
          , new \PHPStrap\Form\Validation\MinLengthValidation(4)
        ])
        , __a('pass2')
        , ''
        , ['col-lg-12']
      );

  } else {
      $form->addFieldWithLabel(
        \PHPStrap\Form\Text::withNameAndValue('nick_or_email', '', 254, [
          new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
          , new \PHPStrap\Form\Validation\MinLengthValidation(4)
        ])
        , __a('nick_or_email')
        , ''
        , ['col-lg-12']
      );
  }


  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('pacmec_forgotten_password'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn btn-dark btn-hover-primary rounded-0 w-100'
  ]);

  $form->Code .= '
    <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
      <a href="'.infosite('siteurl')."/{$GLOBALS['PACMEC']['permanents_links']['%pacmec_signin%']}".'" class="forget-pwd mb-3">'.__a('signin').'</a>
    </div>';
  return isGuest() ? $form : '';
}
add_shortcode('pacmec-form-forgotten-password', 'pacmec_form_forgotten_password');

function pacmec_form_me_info($atts=[], $content='')
{
  global $PACMEC;
  $args = \shortcode_atts([
    "user_id" => \userID(),
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $_user = (array) $PACMEC['session']->user;
  foreach ($PACMEC['fullData'] as $key => $value) { if(in_array($key, array_keys($_user))){ $PACMEC['session']->user->{$key} = $value; } }
  $ME = ($PACMEC['route']->user);
  $form_slug_saveprofile = "saveprofile";
  $result_saveprofile = \pacmec_captcha_check($form_slug_saveprofile);
  $form = new \PHPStrap\Form\Form(
    ''
    // '#pacmec_form_me_info'
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , "Revisa los campos"
    , "OK"
    , ['class'=>'row']);
  $form->setWidths(12,12);
  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation(__a($result_saveprofile), function () use ($PACMEC, $form_slug_saveprofile, $result_saveprofile, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_saveprofile !== 'captcha_disabled')) return false;
      switch ($result_saveprofile) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if($PACMEC['session']->save()==true){
            $result_saveprofile = __a("saveprofile_r_success");
            $form->setSucessMessage($result_saveprofile);
            return true;
          } else {
            $result_saveprofile = __a("saveprofile_r_error");
            return false;
          }
          break;
        default:
          return false;
          break;
      }
      return true;
    })
  ]);
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('names', $ME->user->names, 100, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(2)
    ])
    , __a('names')
    , ''
    , ['col-lg-6 ']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('surname', $ME->user->surname, 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(3)
    ])
    , __a('surname')
    , ''
    , ['col-lg-6']
  );
  $options = [];
  foreach (type_options('identifications') as $a) { $options["{$a->id}"] = $a->name; }
  $form->addFieldWithLabel(
    \PHPStrap\Form\Select::withNameAndOptions('identification_type', $options, $ME->user->identification_type, array_keys($options), ['class'=>'nice-select wide'])
    , __a('identification_type')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('identification_number', $ME->user->identification_number, 30, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ], [])
    , __a('identification_number')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('phone', $ME->user->phone, 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
    ], ['data-mask'=>"(00) 0000-0000"])
    , __a('phone')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('mobile', $ME->user->mobile, 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(9)
    ], ['data-mask'=>"(0#) 000-0000000"])
    , __a('mobile')
    , ''
    , ['col-lg-6']
  );
  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug_saveprofile, 'custom-pacmec'), ['single-input-item mb-3']);
  $form->addSubmitButton(__a('save_changes_btn'), [
    'name'=>"submit-{$form_slug_saveprofile}",
    "class" => 'btn btn-sm btn-outline-dark btn-hover-primary w-100'
  ]);
  return $form;
}
add_shortcode('pacmec-form-me-info', 'pacmec_form_me_info');

function pacmec_form_register($atts=[], $content='')
{
  global $PACMEC;
  $args = \shortcode_atts([
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "createprofile";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    // '#pacmec_form_me_info'
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , "Revisa los campos"
    , "OK"
    , ['class'=>'row']);
  $form->setWidths(12,12);
  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['pass1']) && !empty($PACMEC['fullData']['pass1'])
            && isset($PACMEC['fullData']['pass2']) && !empty($PACMEC['fullData']['pass2'])
            && isset($PACMEC['fullData']['username']) && !empty($PACMEC['fullData']['username'])
            && isset($PACMEC['fullData']['email']) && !empty($PACMEC['fullData']['email'])
            && isset($PACMEC['fullData']['names']) && !empty($PACMEC['fullData']['names'])
            && isset($PACMEC['fullData']['surname']) && !empty($PACMEC['fullData']['surname'])
            && isset($PACMEC['fullData']['identification_type']) && !empty($PACMEC['fullData']['identification_type'])
            && isset($PACMEC['fullData']['identification_number']) && !empty($PACMEC['fullData']['identification_number'])
            && isset($PACMEC['fullData']['phone']) && !empty($PACMEC['fullData']['phone'])
            && isset($PACMEC['fullData']['mobile']) && !empty($PACMEC['fullData']['mobile'])
          ){
            if($PACMEC['fullData']['pass1'] !== $PACMEC['fullData']['pass2']){
              $form->setErrorMessage(__a('change_pass_r_error_not_match'));
              return false;
            }
            $user = new \PACMEC\System\Users();
            $user->set_all($PACMEC['fullData']);
            $user->permissions = infosite('register_group_def');
            $user->hash   = password_hash($PACMEC['fullData']['pass2'], PASSWORD_DEFAULT);
            $user->status = 1;
            // CREA USUARIO ACÁ
            $result_user_creation = $user->create();
            if($result_user_creation !== false && $result_user_creation>0){
              if(infosite('register_email_welcome') !== false){
                // creado correo de bienvenida.
                $mail = new \PACMEC\System\EmailsTemplates((object) ['template_slug'=>infosite('register_email_welcome')]);
                if($mail->isValid()){
                  $mail->set_autot([
                    '%sitelogo%',
                    '%sitename%',
                    '%PreviewText%',
                    '%username%',
                    '%names%',
                    '%surname%',
                    '%password%',
                    '%email%',
                    '%siteurl%',
                    '%email_title%',
                    '%register_email_body%',
                    '%register_from_title_subject%',
                  ], [
                    infosite('siteurl').infosite('sitelogo'),
                    infosite('sitename'),
                    infosite('sitedescr'),
                    $user->username,
                    $user->names,
                    $user->surname,
                    $PACMEC['fullData']['pass2'],
                    $user->email,
                    infosite('siteurl').infosite('homeurl'),
                    sprintf(__a('register_email_title'), "{$user->names} {$user->surname}"),
                    (__a('register_email_body')),
                    sprintf(__a('register_from_title_subject_start'), "{$user->username}"),
                  ]);
                  $result_send = $mail->send(sprintf(__a('register_from_title_subject_start'), infosite('sitename')), $user->email, "{$user->names} {$user->surname}");
                }
              }
              $r_login = $PACMEC['session']->login([
                'nick' => $PACMEC['fullData']['username'],
                'hash' => $PACMEC['fullData']['pass2']
              ]);
              switch ($r_login) {
                case 'no_exist':
                  $form->setErrorMessage(__a('signin_r_no_exist'));
                  return false;
                  break;
                case 'inactive':
                  $form->setErrorMessage(__a('signin_r_inactive'));
                  return false;
                  break;
                case 'error':
                  $form->setErrorMessage(__a('signin_r_error'));
                  return false;
                  break;
                case 'success':
                  $form->setSucessMessage(__a('signin_r_success'));
                  $url = (isset($PACMEC['fullData']['redirect'])) ? ($PACMEC['fullData']['redirect']) : infosite('siteurl').__url_s("/%pacmec_meaccount%");
                  echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
                  return true;
                  break;
                case 'invalid_credentials':
                  $form->setErrorMessage(__a('signin_r_invalid_credentials'));
                  return false;
                  break;
                default:
                  $form->setSucessMessage(__a('register_r_success'));
                  return true;
                  break;
              }
            }

            $form->setErrorMessage(__a('register_r_fail'));
            return false;
          } else {
            $form->setErrorMessage(__a('change_pass_r_invalid_credentials'));
            return false;
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('username', '', 17, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
      , new \PHPStrap\Form\Validation\LambdaValidation(__a('register_nick_exist'), function () use ($PACMEC, $form_slug, $form) {
        if(
          isset($PACMEC['fullData']["submit-{$form_slug}"])
          && isset($PACMEC['fullData']['username'])
        ){
          $model = new \PACMEC\System\Users((object) ['user_nick'=>$PACMEC['fullData']['username']]);
          if($model->isValid()) return false;
          else return true;;
        }
      })
    ], [])
    , __a('username')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('email', '', '', [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\LambdaValidation(__a('register_email_exist'), function () use ($PACMEC, $form_slug, $form) {
        if(
          isset($PACMEC['fullData']["submit-{$form_slug}"])
          && isset($PACMEC['fullData']['email'])
        ){
          $model = new \PACMEC\System\Users((object) ['user_email'=>$PACMEC['fullData']['email']]);
          if($model->isValid()) return false;
          else return true;;
        }
      })
    ], ['type'=>"email"])
    , __a('email')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('names', '', 100, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(2)
    ])
    , __a('names')
    , ''
    , ['col-lg-6 ']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('surname', '', 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(2)
    ])
    , __a('surname')
    , ''
    , ['col-lg-6']
  );
  $options = [];
  foreach (type_options('identifications') as $a) { $options["{$a->id}"] = $a->name; }
  $form->addFieldWithLabel(
    \PHPStrap\Form\Select::withNameAndOptions('identification_type', $options, '', array_keys($options), ['class'=>'nice-select wide'])
    , __a('identification_type')
    , ''
    , ['col-lg-8']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('identification_number', '', 30, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ], [])
    , __a('identification_number')
    , ''
    , ['col-lg-4']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('phone', '', 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
    ], ['data-mask'=>"(00) 0000-0000"])
    , __a('phone')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('mobile', '', 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(9)
    ], ['data-mask'=>"(0#) 000-0000000"])
    , __a('mobile')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('pass1', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('pass1')
    , ''
    , ['col-lg-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('pass2', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('pass2')
    , ''
    , ['col-lg-6']
  );

  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('register_btn'), [
    'name'=>"submit-{$form_slug}",
    // "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
    #"class" => 'btn btn-sm btn-dark btn-hover-primary'
    "class" => 'btn btn btn-dark btn-hover-primary rounded-0 w-100'
  ]);
  return $form;
}
add_shortcode('pacmec-form-register', 'pacmec_form_register');

function pacmec_form_me_change_pass($atts=[], $content='')
{
  global $PACMEC;
  $args = \shortcode_atts([
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "change_pass-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    // infosite('siteurl')."/{$GLOBALS['PACMEC']['permanents_links']['%pacmec_signin%']}"
    ''
    // '#pacmec_form_me_info'
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'row']);
  $form->setWidths(12,12);

  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['current_pass'])
            && isset($PACMEC['fullData']['pass1'])
            && isset($PACMEC['fullData']['pass2'])
          ){
            if($PACMEC['fullData']['pass1'] !== $PACMEC['fullData']['pass2']){
              $form->setErrorMessage(__a('change_pass_r_error_not_match'));
              return false;
            } else {
              if ($PACMEC['session']->check_password($GLOBALS['PACMEC']['fullData']['current_pass']) == true) {
                if($PACMEC['session']->change_pass($PACMEC['fullData']['pass2']) == true){
                  $form->setSucessMessage(__a('change_pass_r_success'));
                  return true;
                } else {
                  $form->setErrorMessage(__a('change_pass_r_error'));
                  return false;
                }
              } else {
                $form->setErrorMessage(__a('change_pass_r_auth_invalid'));
                return false;
              }
            }
          } else {
            $form->setErrorMessage(__a('change_pass_r_invalid_credentials'));
            return false;
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);

  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('current_pass', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('current_pass')
    , ''
    , ['col-lg-12']
  );

  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('pass1', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('pass1')
    , ''
    , ['col-lg-6']
  );

  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('pass2', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('pass2')
    , ''
    , ['col-lg-6']
  );

  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('save_changes_btn'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
  ]);

  return $form;
}
add_shortcode('pacmec-form-me-change-pass', 'pacmec_form_me_change_pass');

function pacmec_form_me_change_access($atts=[], $content='')
{
  global $PACMEC;
  $_user = (array) $PACMEC['session']->user;
  foreach ($PACMEC['fullData'] as $key => $value) { if(in_array($key, array_keys($_user))){ $PACMEC['session']->user->{$key} = $value; } }
  $ME = ($PACMEC['route']->user);
  $args = \shortcode_atts([
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "change_access-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    // infosite('siteurl')."/{$GLOBALS['PACMEC']['permanents_links']['%pacmec_signin%']}"
    ''
    // '#pacmec_form_me_info'
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'row']);
  $form->setWidths(12,12);

  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['current_pass'])
          ){
            if ($PACMEC['session']->check_password($GLOBALS['PACMEC']['fullData']['current_pass']) == true) {
              if($PACMEC['session']->save_info_access()==true){
                $form->setSucessMessage(__a('saveaccess_r_success'));
                return true;
              } else {
                $form->setErrorMessage(__a('saveaccess_r_error'));
                return false;
              }
            } else {
              $form->setErrorMessage(__a('change_pass_r_invalid_credentials'));
              return false;
            }
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);

  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('username', $ME->user->username, 17, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
      , new \PHPStrap\Form\Validation\LambdaValidation(__a('register_nick_exist'), function () use ($PACMEC, $form_slug, $form) {
        if(
          isset($PACMEC['fullData']["submit-{$form_slug}"])
          && isset($PACMEC['fullData']['username'])
        ){
          $model = new \PACMEC\System\Users((object) ['user_nick'=>$PACMEC['fullData']['username']]);
          if($model->isValid()) return false;
          else return true;;
        }
      })
    ], [])
    , __a('username')
    , ''
    , ['col-lg-6']
  );

  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('email', $ME->user->email, '', [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
    ], ['type'=>"email"])
    , __a('email')
    , ''
    , ['col-lg-6']
  );

  $form->addFieldWithLabel(
    \PHPStrap\Form\Password::withNameAndValue('current_pass', '', 32, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(4)
    ])
    , __a('current_pass')
    , ''
    , ['col-lg-12']
  );

  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('save_changes_btn'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
  ]);

  return $form;
}
add_shortcode('pacmec-form-me-change-access', 'pacmec_form_me_change_access');

function pacmec_me_welcome_small($atts=[], $content='')
{
  global $PACMEC;
  $ME = ($PACMEC['route']->user);
  return
  \PHPStrap\Util\Html::tag('div',
    \PHPStrap\Util\Html::tag('div',
      \PHPStrap\Util\Html::tag('p', __a('hello').', '. \PHPStrap\Util\Html::tag('strong', $ME->user->username, [], []), [], [])
    , ['welcome'], [])
    . \PHPStrap\Util\Html::tag('p', __a('me_account_descr'), ['mb-0'], [])
  , [], []);

}
add_shortcode('pacmec-me-welcome-small', 'pacmec_me_welcome_small');

function pacmec_me_welcome_medium($atts=[], $content='')
{
  global $PACMEC;
  $ME = ($PACMEC['route']->user);
  return
  \PHPStrap\Util\Html::tag('div'
  , \PHPStrap\Util\Html::tag('div',
      \PHPStrap\Util\Html::tag('h3', __a('me_account'), ['title'], [])
      . \PHPStrap\Util\Html::tag('div',
        \PHPStrap\Util\Html::tag('p', __a('hello').', '. \PHPStrap\Util\Html::tag('strong', $ME->user->username, [], []), [], [])
      , ['welcome'], [])
      . \PHPStrap\Util\Html::tag('p', __a('me_account_descr'), ['mb-0'], [])
    , ['myaccount-content'], [])
  , ['tab-pane'], ['id'=>"dashboad", 'role'=>"tabpanel"]);
}
add_shortcode('pacmec-me-welcome-medium', 'pacmec_me_welcome_medium');

function pacmec_me_orders_table($atts=[], $content='')
{
  return \PACMEC\System\Orders::table_list_html(\PACMEC\System\Orders::get_all_by_user_id());
}
add_shortcode('pacmec-me-orders-table', 'pacmec_me_orders_table');

function pacmec_me_notifications_table($atts=[], $content='')
{
  global $PACMEC;
  return \PACMEC\System\Notifications::table_list_html(\PACMEC\System\Notifications::get_all_by_user_id(null, true));
}
add_shortcode('pacmec-me-notifications-table', 'pacmec_me_notifications_table');

function pacmec_me_payments_table($atts=[], $content='')
{
  return \PACMEC\System\Payments::table_list_html(\PACMEC\System\Payments::get_all_by_user_id());
}
add_shortcode('pacmec-me-payments-table', 'pacmec_me_payments_table');

function pacmec_me_addresses_table($atts=[], $content='')
{
  global $PACMEC;
  $add = "";
  if(isset($PACMEC['fullData']['remove_id']) && !empty($PACMEC['fullData']['remove_id'])) {
    $result_remove = \PACMEC\System\GeoAddresses::remove_from_user($PACMEC['fullData']['remove_id']);
    if($result_remove!==false&&$result_remove>0){
      $add .= \PHPStrap\Util\Html::tag('div', __a('remove_address_success'), ['alert alert-success']);
    } else {
      $add .= \PHPStrap\Util\Html::tag('div', __a('remove_address_fail'), ['alert alert-success']);
    }
  }
  return $add.\PACMEC\System\GeoAddresses::table_list_html(\PACMEC\System\GeoAddresses::get_all_by_user_id());
}
add_shortcode('pacmec-me-addresses-table', 'pacmec_me_addresses_table');

function pacmec_order_apply_coupon($atts=[], $content='')
{
  global $PACMEC;
  $args = \shortcode_atts([
    "order_id"=>false
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "order-apply-cupon-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  if($args['order_id'] == false) return "";
  $order = new \PACMEC\System\Orders((object) ['order_id'=>$args['order_id']]);
  if(!$order->isValid()) return (__a('order_not_match'));
  if($order->pay_enabled==false) return __a('order_payment_not_available');

  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'form row']);
  $form->setWidths(12,12);
  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form, $order) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['coupon_code'])
          ){
            $coupon = new \PACMEC\System\Coupons((object) [
              "coupon_code" => $PACMEC['fullData']['coupon_code']
            ]);

            if($coupon->isValid() && $PACMEC['fullData']['coupon_code'] === $coupon->code){
              $checker_gbl = \decrypt($coupon->hash, '*');
              $checker_host = \decrypt($coupon->hash, 'fym.managertechnology.com.co');
              $code_v1 = \encrypt($PACMEC['fullData']['coupon_code'], $PACMEC['host']);
              if($PACMEC['fullData']['coupon_code'] === $checker_gbl || $PACMEC['fullData']['coupon_code'] === $checker_host){
                if($coupon->redeemed_date !== null){
                  $form->setErrorMessage(__a('coupon_already_redeemed'));
                  return false;
                }
                if($coupon->expiration_date !== null){
                  $datetime1 = date_create('now');
                  $datetime2 = date_create($coupon->expiration_date);
                  if($datetime1 >= $datetime2){
                    $form->setErrorMessage(__a('coupon_expired'));
                    return false;
                  }
                }
                $current_total = (float) $order->total;
                $coupon_total  = (float) $coupon->amount;
                $new_total  = $current_total - $coupon_total;
                $coupon_item = new \PACMEC\System\OrdersItems();
                $coupon_item->order_id = $order->getId();
                $coupon_item->type = "coupon";
                $coupon_item->ref = $coupon->getId();
                $coupon_item->discount_amount = $coupon->amount;
                $coupon_create_result = $coupon_item->create();
                if($coupon_create_result !== false && $coupon_create_result>0){
                  $cou_pay = $coupon->redeemed();
                  if($cou_pay == true){
                    $form->setSucessMessage(__a('coupon_applied'));
                    echo "<meta http-equiv=\"refresh\" content=\"0\" />";
                    return true;
                  }
                }
                $form->setErrorMessage(__a('coupon_error_applied'));
                return false;
              } else {
                $form->setErrorMessage(__a('coupon_not_match_host'));
                return false;
              }
            } else {
              $form->setErrorMessage(__a('coupon_not_match'));
              return false;
            }
            return false;
          }
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('coupon_code', '', 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
    ])
    , __a('coupon_code')
    , ''
    , ['col-lg-7 col-sm-12']
  );

  $form->Code .= \PHPStrap\Util\Html::tag('div',
  \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3'])
  , ['col-lg-7 col-sm-12']);

  $form->Code .= '<div class="col-lg-12 col-sm-12">';
  $form->addSubmitButton(__a('coupon_apply_btn'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn-sm btn-dark btn-hover-primary'
  ]);
  $form->Code .= '</div>';
  return $form;
}
add_shortcode('pacmec-order-apply-coupon', 'pacmec_order_apply_coupon');

function pacmec_me_add_address($atts=[], $content='')
{
  global $PACMEC;
  $form_slug = "site-address-add-form-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Normal
    , 'Error:'
    , "OK"
    , ['class'=>'checkbox-form row', 'id'=>$form_slug]);
  $form->setWidths(12,12);

  $form->Code .= '<style>
  .select2-container { width:100% !important; }
  .select2-container--default .select2-selection--single { height: 42px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:42px; }
  </style>';

  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']['country']) && !empty($PACMEC['fullData']['country'])
            && isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['city']) && !empty($PACMEC['fullData']['city'])
            && isset($PACMEC['fullData']['main_road']) && !empty($PACMEC['fullData']['main_road'])
            && isset($PACMEC['fullData']['main_road_text']) && !empty($PACMEC['fullData']['main_road_text'])
            && isset($PACMEC['fullData']['minor_road']) && !empty($PACMEC['fullData']['minor_road'])
            && isset($PACMEC['fullData']['number']) && !empty($PACMEC['fullData']['number'])
            && isset($PACMEC['fullData']['extra']) && !empty($PACMEC['fullData']['extra'])
            && isset($PACMEC['fullData']['extra_text']) && !empty($PACMEC['fullData']['extra_text'])
          ){
            $code_country = null;
            if(isset($PACMEC['fullData']['country']) && !empty($PACMEC['fullData']['country'])){
              foreach ($PACMEC['geo']['countries'] as $i => $c){
                if($c->id == $PACMEC['fullData']['country']) {
                  $code_country = $c;
                  break;
                }
              }
            }
            $obj_city = null;
            foreach ($PACMEC['geo']['cities'][$code_country->code] as $i => $c){
              if($c->id == $PACMEC['fullData']['city']) {
                $obj_city = $c;
                break;
              }
            }
            $obj_main_road = null;
            foreach (type_options('geo_types_vias') as $option):
              if($PACMEC['fullData']['main_road'] == $option->id) {
                $obj_main_road = $option;
                break;
              }
            endforeach;
            $obj_extra = null;
            foreach (type_options('geo_extra') as $option):
              if($PACMEC['fullData']['extra'] == $option->id) {
                $obj_extra = $option;
                break;
              }
            endforeach;
            $resutl_create = new \PACMEC\System\GeoAddresses();
            $resutl_create->country = $PACMEC['fullData']['country'];
            $resutl_create->city = $PACMEC['fullData']['city'];
            $resutl_create->main_road = $PACMEC['fullData']['main_road'];
            $resutl_create->main_road_text = $PACMEC['fullData']['main_road_text'];
            $resutl_create->minor_road = $PACMEC['fullData']['minor_road'];
            $resutl_create->number = $PACMEC['fullData']['number'];
            $resutl_create->extra = $PACMEC['fullData']['extra'];
            $resutl_create->extra_text = $PACMEC['fullData']['extra_text'];
            $resutl_create->details = isset($PACMEC['fullData']['details']) ? $PACMEC['fullData']['details'] : '';
            $resutl_create->mini = "{$obj_main_road->code} {$resutl_create->main_road_text} "
              . "# {$resutl_create->minor_road}-{$resutl_create->number} {$obj_extra->code} {$resutl_create->extra_text}"
              . ((!empty($resutl_create->details)) ? " ({$resutl_create->details})" : "")
              . ", {$obj_city->name}, {$code_country->name}";
            $resutl_create->full = "{$obj_main_road->name} {$resutl_create->main_road_text} "
              . "# {$resutl_create->minor_road}-{$resutl_create->number} {$obj_extra->name} {$resutl_create->extra_text}"
              . ((!empty($resutl_create->details)) ? " ({$resutl_create->details})" : "")
              . ", {$obj_city->name}, {$code_country->name}";
            $result_create = $resutl_create->create();
            if($resutl_create->id>0){
              $result_user = $resutl_create->add_in_user();
              if($result_user!==false&&$result_user>0){
                $url = infosite('siteurl').__url_s("/%pacmec_meaccount%?tab=me_addresses");
                echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url}'\" />";
                $form->setSucessMessage(__a('add_address_success'));
                return true;
              } else {
                $form->setErrorMessage(__a('add_address_fail'));
                return false;
              }
              $form->setErrorMessage(__a('add_address_fail'));
              return false;
            } else {
              $form->setErrorMessage(__a('add_address_fail'));
              return false;
            }
          }
          $form->setErrorMessage(__a('site_create_order_fail'));
          return false;
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  \pacmec_add_part_form_new_address($form);
  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);
  $form->addSubmitButton(__a('save_changes_btn'), [
  'name'=>"submit-{$form_slug}",
  "class" => 'btn btn-dark btn-hover-primary rounded-0 w-100'
  //"class" => 'btn btn-sm btn-dark btn-hover-primary'
  ]);
  $form->Code .= '
  <script>
  function selectCountry(){
  $(document).ready(function() {
    $(".js-item-basic-single").select2({
      placeholder: "'.__a('select_an_option').'",
    });
  });
  $(\'select[name="country"]\').on(\'change\', function() {
    document.getElementById("'.$form_slug.'").submit();
  });
  }
  window.addEventListener(\'load\', selectCountry);
  </script>';
  return $form;
}
add_shortcode('pacmec-me-add-address', 'pacmec_me_add_address');

function pacmec_add_part_form_new_address($form)
{
  global $PACMEC;
  $code_country = null;
  if(isset($PACMEC['fullData']['country']) && !empty($PACMEC['fullData']['country'])){
    foreach ($PACMEC['geo']['countries'] as $i => $c){
      if($c->id == $PACMEC['fullData']['country']) {
        $code_country = $c;
        break;
      }
    }
  }
  $html = "";
  $options_countries = [''=>__a('select_an_option')];
  $options_countries_k = [];
  foreach ($PACMEC['geo']['countries'] as $i => $country) $options_countries_k["{$country->id}"] = $options_countries["{$country->id}"] = "{$country->name} [{$country->code}]";
  $options_cities = [''=>__a('select_an_option')];
  $options_cities_k = [];
  if ($code_country!==null) foreach ($PACMEC['geo']['cities'][$code_country->code] as $i => $city) $options_cities_k[$city->id] = $options_cities[$city->id] = $city->name;
  $options_geo_types_vias = [''=>__a('select_an_option')];
  $options_geo_types_vias_k = [];
  foreach (type_options('geo_types_vias') as $option) $options_geo_types_vias_k[$option->id] = $options_geo_types_vias[$option->id] = "{$option->name} [{$option->code}]";
  $options_geo_extra = [''=>__a('select_an_option')];
  $options_geo_extra_k = [];
  foreach (type_options('geo_extra') as $option):
    $options_geo_extra_k[$option->id] = $options_geo_extra[$option->id] = "{$option->name} [{$option->code}]";
  endforeach;

  $form->addFieldWithLabel(
    new \PHPStrap\Form\Select(
      $options_countries
      , ""
      , [
        'class'  => 'js-item-basic-single'
        , 'name' => 'country'
        , 'id' => 'country'
        , 'required' => ''
      ]
      , [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_countries_k))
      ]
    )
    , __a('country') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6 checkout-form-list']
  );
  $form->addFieldWithLabel(
    new \PHPStrap\Form\Select(
      $options_cities
      , ""
      , [
        'class'  => 'js-item-basic-single'
        , 'name' => 'city'
        , 'id' => 'city'
        , 'required' => ''
      ]
      , [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_cities_k))
      ]
    )
    , __a('city') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 checkout-form-list']
  );
  $form->addFieldWithLabel(
    new \PHPStrap\Form\Select(
      $options_geo_types_vias
      , ""
      , [
        'class'  => 'js-item-basic-single'
        , 'name' => 'main_road'
        , 'id' => 'main_road'
        , 'required' => ''
      ]
      , [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_geo_types_vias_k))
      ]
    )
    , __a('address') . ' <span class="required">*</span>'
    , ''
    , ['col-md-4 checkout-form-list']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('main_road_text',
      ""
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , ''
    , ''
    , ['col-md-2']
  );
  $form->Code .= \PHPStrap\Util\Html::tag('div', '<br>#', ['col-md-1']);

  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('minor_road',
      ""
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , ''
    , ''
    , ['col-md-2']
  );
  $form->Code .= \PHPStrap\Util\Html::tag('div', '<br>-', ['col-md-1']);
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('number',
      ""
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , ''
    , ''
    , ['col-md-2']
  );
  $form->addFieldWithLabel(
    new \PHPStrap\Form\Select(
      $options_geo_extra
      , ""
      , [
        'class'  => 'js-item-basic-single'
        , 'name' => 'extra'
        , 'id' => 'extra'
        , 'required' => ''
      ]
      , [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_geo_extra_k))
      ]
    )
    , '&nbsp;'
    , ''
    , ['col-md-4 checkout-form-list']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('extra_text',
      ""
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , ''
    , ''
    , ['col-md-4']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('details',
      ""
    , 254, [
    ])
    , ''
    , ''
    , ['col-md-4']
  );
  return $form;
}

function pacmec_form_create_order_site($atts=[], $content='')
{
  global $PACMEC;
  $form_slug = "site-order-create-form-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Normal
    , 'Error:'
    , "OK"
    , ['class'=>'checkbox-form row', 'id'=>$form_slug]);
  $form->setWidths(12,12);

  $form->Code .= '<style>
  .select2-container { width:100% !important; }
  .select2-container--default .select2-selection--single { height: 42px; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:42px; }
  </style>';


  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(count($PACMEC['session']->shopping_cart)<=0) {
            $form->setErrorMessage(__a('order_in_black'));
            return false;
          }
          $address_id = 0;
          if (isset($PACMEC['fullData']['address']) && !empty($PACMEC['fullData']['address']) && isset($PACMEC['fullData']["submit-{$form_slug}"])){
            $resutl_create = new \PACMEC\System\GeoAddresses((object) ['address_id'=>$PACMEC['fullData']['address']]);
            $address_id = $resutl_create!==false && $resutl_create->isValid() ? $resutl_create->id : 0;
          }
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['country']) && !empty($PACMEC['fullData']['country'])
            && isset($PACMEC['fullData']['city']) && !empty($PACMEC['fullData']['city'])
            && isset($PACMEC['fullData']['main_road']) && !empty($PACMEC['fullData']['main_road'])
            && isset($PACMEC['fullData']['main_road_text']) && !empty($PACMEC['fullData']['main_road_text'])
            && isset($PACMEC['fullData']['minor_road']) && !empty($PACMEC['fullData']['minor_road'])
            && isset($PACMEC['fullData']['number']) && !empty($PACMEC['fullData']['number'])
            && isset($PACMEC['fullData']['extra']) && !empty($PACMEC['fullData']['extra'])
            && isset($PACMEC['fullData']['extra_text']) && !empty($PACMEC['fullData']['extra_text'])
          ){
            $code_country = null;
            if(isset($PACMEC['fullData']['country']) && !empty($PACMEC['fullData']['country'])){
              foreach ($PACMEC['geo']['countries'] as $i => $c){
                if($c->id == $PACMEC['fullData']['country']) {
                  $code_country = $c;
                  break;
                }
              }
            }
            $obj_city = null;
            foreach ($PACMEC['geo']['cities'][$code_country->code] as $i => $c){
              if($c->id == $PACMEC['fullData']['city']) {
                $obj_city = $c;
                break;
              }
            }
            $obj_main_road = null;
            foreach (type_options('geo_types_vias') as $option):
              if($PACMEC['fullData']['main_road'] == $option->id) {
                $obj_main_road = $option;
                break;
              }
            endforeach;
            $obj_extra = null;
            foreach (type_options('geo_extra') as $option):
              if($PACMEC['fullData']['extra'] == $option->id) {
                $obj_extra = $option;
                break;
              }
            endforeach;
            $resutl_create = new \PACMEC\System\GeoAddresses();
            $resutl_create->country = $PACMEC['fullData']['country'];
            $resutl_create->city = $PACMEC['fullData']['city'];
            $resutl_create->main_road = $PACMEC['fullData']['main_road'];
            $resutl_create->main_road_text = $PACMEC['fullData']['main_road_text'];
            $resutl_create->minor_road = $PACMEC['fullData']['minor_road'];
            $resutl_create->number = $PACMEC['fullData']['number'];
            $resutl_create->extra = $PACMEC['fullData']['extra'];
            $resutl_create->extra_text = $PACMEC['fullData']['extra_text'];
            $resutl_create->details = isset($PACMEC['fullData']['details']) ? $PACMEC['fullData']['details'] : '';
            $resutl_create->mini = "{$obj_main_road->code} {$resutl_create->main_road_text} "
              . "# {$resutl_create->minor_road}-{$resutl_create->number} {$obj_extra->code} {$resutl_create->extra_text}"
              . ((!empty($resutl_create->details)) ? " ({$resutl_create->details})" : "")
              . ", {$obj_city->name}, {$code_country->name}";
            $resutl_create->full = "{$obj_main_road->name} {$resutl_create->main_road_text} "
              . "# {$resutl_create->minor_road}-{$resutl_create->number} {$obj_extra->name} {$resutl_create->extra_text}"
              . ((!empty($resutl_create->details)) ? " ({$resutl_create->details})" : "")
              . ", {$obj_city->name}, {$code_country->name}";
            $address_r = $resutl_create->create();
            if($resutl_create->isValid()) $address_id = $resutl_create->id;
          }

          if(
            $address_id!==false && $address_id>0
            && isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['identification_type']) && !empty($PACMEC['fullData']['identification_type'])
            && isset($PACMEC['fullData']['identification_number']) && !empty($PACMEC['fullData']['identification_number'])
            && isset($PACMEC['fullData']['names']) && !empty($PACMEC['fullData']['names'])
            && isset($PACMEC['fullData']['surname']) && !empty($PACMEC['fullData']['surname'])
            && isset($PACMEC['fullData']['email']) && !empty($PACMEC['fullData']['email'])
            && isset($PACMEC['fullData']['phone']) && !empty($PACMEC['fullData']['phone'])
            && isset($PACMEC['fullData']['mobile']) && !empty($PACMEC['fullData']['mobile'])
          ){
            $order = new PACMEC\System\Orders();
            $order->identification_type       = $PACMEC['fullData']['identification_type'];
            $order->identification_number     = $PACMEC['fullData']['identification_number'];
            $order->names                     = $PACMEC['fullData']['names'];
            $order->surname                   = $PACMEC['fullData']['surname'];
            $order->company_name              = $PACMEC['fullData']['company_name'];
            $order->email                     = $PACMEC['fullData']['email'];
            $order->phone                     = $PACMEC['fullData']['phone'];
            $order->mobile                    = $PACMEC['fullData']['mobile'];
            if(isset($PACMEC['fullData']['company_name']) && !empty($PACMEC['fullData']['company_name'])) $order->company_name = $PACMEC['fullData']['company_name'];
            $order->customer_ip               = \getIpRemote();
            $order->obs                       = __a("order_create_from_site");
            $insert = $order->create();
            if($order->id>0){
              $resutl_create->add_in_order($order->id);
              // \PACMEC\System\OrdersItems::add_in_order();
              // $order
              // order_id
              // $form->setSucessMessage(__a('order_create_success'));
              $ins_carts = [];
              foreach ($PACMEC['session']->shopping_cart as $key => $item) {
                $k_vals = explode(':', $key);
                switch ($k_vals[0]) {
                  case 'product':
                  if(isset($k_vals[1])){
                    $ab = new \PACMEC\System\OrdersItems();
                    $ab->order_id = $order->id;
                    $ab->type = $k_vals[0];
                    $ab->ref   = $k_vals[1];
                    $ab->quantity = $item->quantity;
                    $ab->unit_price = $item->data->price;
                    $result = $ab->create();
                    if($result!==false && $result>0){
                      $PACMEC['session']->remove_from_cart($item->id, $item->session_id);
                    }
                  }
                  break;
                  default:
                  break;
                }
              }
              $mail = new \PACMEC\System\EmailsTemplates((object) ['template_slug'=>infosite('mail_new_order')]);
              if($mail->isValid()){
                $mail->set_autot([
                  '%sitelogo%',
                  '%sitename%',
                  '%PreviewText%',
                  '%siteurl%',
                  '%names%',
                  '%surname%',
                  '%order_id%',
                  '%url%',
                ], [
                  infosite('siteurl').infosite('sitelogo'),
                  infosite('sitename'),
                  infosite('sitedescr'),
                  infosite('siteurl').infosite('homeurl'),
                  $order->names,
                  $order->surname,
                  $order->id,
                  $order->link_view,
                ]);
                $result_send = $mail->send(__a('order').$order->id, $order->email, "{$order->names} {$order->surname}");
              }

              $form->setSucessMessage(__a('order_create_success'));
              echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$order->link_view}'\" />";
              return true;
            }
          } else {
            $form->setErrorMessage(__a('form_invalid'));
            return false;
          }
          $form->setErrorMessage(__a('form_invalid'));
          return false;
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);

  $options_identifications = [''=>__a('select_an_option')];
  $options_identifications_k = [];
  foreach (type_options('identifications') as $a) $options_identifications_k["{$a->id}"] = $options_identifications["{$a->id}"] = "{$a->name} [{$a->code}]";
  $form->Code .= "<h3 class=\"title\">".__a('order_info')."</h3>";
  $form->addFieldWithLabel(
    new \PHPStrap\Form\Select(
      $options_identifications
      , isset($PACMEC['fullData']['country']) ? $PACMEC['fullData']['country'] : ((\isUser()) ? "{$PACMEC['session']->user->identification_type}" : "")
      , [
        'class'  => 'myniceselect nice-select wide rounded-0'
        , 'name' => 'identification_type'
        , 'id' => 'identification_type'
        , 'required' => ''
        , 'value' => isset($PACMEC['fullData']['country']) ? $PACMEC['fullData']['country'] : ((\isUser()) ? "{$PACMEC['session']->user->identification_type}" : "")
      ]
      , [
        new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_identifications_k))
      ]
    )
    , __a('identification_type') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6 checkout-form-list']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('identification_number',
      ((\isUser()) ? "{$PACMEC['session']->user->identification_number}" : "")
    , 25, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , __a('identification_number') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('names',
      ((\isUser()) ? "{$PACMEC['session']->user->names}" : "")
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , __a('names') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('surname',
      ((\isUser()) ? "{$PACMEC['session']->user->names}" : "")
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ])
    , __a('surname') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('company_name',
      ""
    , 254, [
    ])
    , __a('company_name')
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('email',
      ((\isUser()) ? "{$PACMEC['session']->user->email}" : "")
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\EmailValidation(__a('email_invalid'))
    ])
    , __a('email') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('phone',
      ((\isUser()) ? "{$PACMEC['session']->user->phone}" : "")
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ], ['data-mask'=>"(00) 0000-0000"])
    , __a('phone') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->addFieldWithLabel(
    \PHPStrap\Form\Text::withNameAndValue('mobile',
      ((\isUser()) ? "{$PACMEC['session']->user->mobile}" : "")
    , 254, [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
    ], ['data-mask'=>"(0#) 000-0000000"])
    , __a('mobile') . ' <span class="required">*</span>'
    , ''
    , ['col-md-6 mb-6']
  );
  $form->Code .= "<h3 class=\"title\">".__a('shipping_info')."</h3>";

  if (\isUser() && count(\PACMEC\System\GeoAddresses::get_all_by_user_id())>0 && infosite('address_in_users')==true):
    $options_addressess = [''=>__a('select_an_option')];
    $options_addressess_k = [];
    foreach (\PACMEC\System\GeoAddresses::get_all_by_user_id() as $address){
      $options_addressess_k["{$address->id}"] = $options_addressess["{$address->id}"] = "{$address->mini}";
    }

    $form->addFieldWithLabel(
      new \PHPStrap\Form\Select(
        $options_addressess
        , ""
        , [
          'class'  => 'myniceselect nice-select wide rounded-0'
          , 'name' => 'address'
          , 'id' => 'address'
          , 'required' => ''
        ]
        , [
          new \PHPStrap\Form\Validation\InListValidation(__a('required_field'), array_keys($options_addressess_k))
        ]
      )
      , __a('address') . ' <span class="required">*</span>'
      , ''
      , ['col-md-12 mb-6 checkout-form-list']
    );
  else:
    \pacmec_add_part_form_new_address($form);
  endif;

  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11)."-login", $form_slug, 'custom-pacmec'), ['single-input-item mb-3']);

  $form->addSubmitButton(__a('place_order'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn-dark btn-hover-primary rounded-0 w-100'
    //"class" => 'btn btn-sm btn-dark btn-hover-primary'
  ]);

  $form->Code .= '
  <script>
  function selectCountry(){
    $(document).ready(function() {
      $(".js-item-basic-single").select2({
        placeholder: "'.__a('select_an_option').'",
      });
    });
    $(\'select[name="country"]\').on(\'change\', function() {
      document.getElementById("'.$form_slug.'").submit();
    });
  }
  window.addEventListener(\'load\', selectCountry);
  </script>';

  if($form->isValid()){

  }

  return $form;
}
add_shortcode('pacmec-form-create-order-site', 'pacmec_form_create_order_site');

function pacmec_comment_form($atts=[], $content='')
{
  global $PACMEC;
  $ME = $PACMEC['session']->user;
  $args = \shortcode_atts([
    "url"=>false
  ], $atts);
  $is_error    = null;
  $msg         = null;
  $form_slug = "create-comment-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  if($args['url'] == false) $args['url'] = infosite('siteurl').$PACMEC['path'];
  $user_id = (\userID()>0) ? \userID() : null;

  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "OK"
    , ['class'=>'form row']);
  $form->setWidths(12,12);
  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form, $ME, $args) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':
          if(
            isset($PACMEC['fullData']["submit-{$form_slug}"])
            && isset($PACMEC['fullData']['display_name']) && !empty($PACMEC['fullData']['display_name'])
            && isset($PACMEC['fullData']['email']) && !empty($PACMEC['fullData']['email'])
            && isset($PACMEC['fullData']['comment']) && !empty($PACMEC['fullData']['comment'])
            && isset($PACMEC['fullData']['vote']) && !empty($PACMEC['fullData']['vote'])
          ){
            $comment                        = new \PACMEC\System\Comments();
            $comment->uri                   = $args['url'];
            $comment->display_name          = $PACMEC['fullData']['display_name'];
            $comment->email                 = $PACMEC['fullData']['email'];
            $comment->comment               = $PACMEC['fullData']['comment'];
            $comment->vote                  = $PACMEC['fullData']['vote'];
            if(\isUser())                   $comment->user_id = \userID();
            $comment->create();
            if($comment->isValid()){
              echo "<meta http-equiv=\"refresh\" content=\"0\" />";
              $form->setSucessMessage(__a('add_comment_r_success'));
              return true;
            } else {
              $form->setErrorMessage(__a('add_comment_r_fail'));
              return false;
            }
            $form->setErrorMessage(__a('add_comment_r_fail'));
            return false;
          }
          $form->setErrorMessage(__a('form_invalid'));
          return false;
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  $form->Code .= \PHPStrap\Util\Html::tag('div',
    \PHPStrap\Util\Html::tag('h3', __a('add_comment_title'), ['title'])
    . \PHPStrap\Util\Html::tag('p', __a('email_not_required_fields_marked_ask'), [])
  , ['col-sm-12'], []);

  if(\isUser()){
    $labels = [
      "{$ME->names} {$ME->surname}" => "{$ME->names} {$ME->surname}",
      "{$ME->username}" => "{$ME->username}",
    ];

    $form->addFieldWithLabel(
      \PHPStrap\Form\Select::withNameAndOptions('display_name', $labels, "{$ME->names} {$ME->surname}", array_keys($labels), ['class'=>'nice-select wide'])
      , __a('display_name') . ' <span class="required">*</span>'
      , ''
      , ['col-lg-12 col-sm-12']
    );
    $form->hidden([
      [
        "name"  => "email",
        "value" => $ME->email
      ]
    ]);
  } else {
    $form->addFieldWithLabel(
      \PHPStrap\Form\Text::withNameAndValue('display_name', '', 254, [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\MinLengthValidation(6)
      ])
      , __a('display_name') . ' <span class="required">*</span>'
      , ''
      , ['col-lg-6 col-sm-12']
    );
    $form->addFieldWithLabel(
      \PHPStrap\Form\Text::withNameAndValue('email', '', 254, [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        , new \PHPStrap\Form\Validation\EmailValidation(__a('email_invalid'))
        , new \PHPStrap\Form\Validation\MinLengthValidation(6)
      ])
      , __a('email') . ' <span class="required">*</span>'
      , ''
      , ['col-lg-6 col-sm-12']
    );
  }


  $votes = [
    5 => 'Excelente',
    4 => 'Bueno',
    3 => 'Normal',
    2 => 'Bajo',
    1 => 'Pesimo',
  ];
  $form->addFieldWithLabel(
    \PHPStrap\Form\Select::withNameAndOptions('vote', $votes, 5, array_keys($votes), ['class'=>'nice-select wide'])
    , __a('vote') . ' <span class="required">*</span>'
    , ''
    , ['col-lg-12 col-sm-12']
  );

  $form->addFieldWithLabel(
    new \PHPStrap\Form\Textarea('', [
      'class'=>'form-control comment-notes',
      "name" => "comment"
    ], [
      new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
      , new \PHPStrap\Form\Validation\MinLengthValidation(6)
    ])
    , __a('comment') . ' <span class="required">*</span>'
    , ''
    , ['col-lg-12 col-sm-12 comment-form-comment']
  );
  $form->Code .= \PHPStrap\Util\Html::tag('div', "<br/>".\pacmec_captcha_widget_html("pacmec-captcha-".randString(11), $form_slug, 'custom-pacmec'), ['col-sm-12']);
  $form->Code .= "<div class=\"clearfix\"><br></div>";
  $form->addSubmitButton(__a('add_comment_btn'), [
    'name'=>"submit-{$form_slug}",
    "class" => 'btn btn-dark btn-hover-primary rounded-1'
    //"class" => 'btn btn-sm btn-dark btn-hover-primary'
  ]);

  return $form;
}
add_shortcode('pacmec-comment-form', 'pacmec_comment_form');

function pacmec_contact_form($atts, $content="")
{
  global $PACMEC;
  $form_slug = "site-contact-form-pacmec";
  $result_captcha = \pacmec_captcha_check($form_slug);
  $form = new \PHPStrap\Form\Form(
    ''
    , 'POST'
    , PHPStrap\Form\FormType::Normal
    , 'Error:'
    , "OK"
    , ['class'=>'row contact-form', 'id'=>$form_slug]);
  $form->setWidths(12,12);

  $form->setGlobalValidations([
    new \PHPStrap\Form\Validation\LambdaValidation('', function () use ($PACMEC, $form_slug, $result_captcha, $form) {
      if(!isset($PACMEC['fullData']["adcopy_response"]) && ($result_captcha !== 'captcha_disabled')) return false;
      switch ($result_captcha) {
        case 'captcha_r_success':
        case 'captcha_disabled':

          $form->setErrorMessage(__a('form_invalid'));
          return false;
          break;
        default:
          $form->setErrorMessage(__a($result_captcha));
          return false;
          break;
      }
      return true;
    })
  ]);
  return $form;
  /*
  <form action="https://htmlmail.hasthemes.com/rezaul/destry.php" id="contact-form" method="post">
      <div class="row">
          <div class="col-12">
              <div class="row">
                  <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                      <div class="input-item mb-4">
                          <input class="input-item" type="text" placeholder="Your Name *" name="name">
                      </div>
                  </div>
                  <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                      <div class="input-item mb-4">
                          <input class="input-item" type="email" placeholder="Email *" name="email">
                      </div>
                  </div>
                  <div class="col-12" data-aos="fade-up" data-aos-delay="300">
                      <div class="input-item mb-4">
                          <input class="input-item" type="text" placeholder="Subject *" name="subject">
                      </div>
                  </div>
                  <div class="col-12" data-aos="fade-up" data-aos-delay="400">
                      <div class="input-item mb-8">
                          <textarea class="textarea-item" name="message" placeholder="Message"></textarea>
                      </div>
                  </div>
                  <div class="col-12" data-aos="fade-up" data-aos-delay="500">
                      <button type="submit" id="submit" name="submit" class="btn btn-dark btn-hover-primary rounded-0">Send A Message</button>
                  </div>
                  <p class="col-8 form-message mb-0"></p>
              </div>
          </div>
      </div>
  </form>
  <p class="form-messege"></p>

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
	</form>*/
}
add_shortcode('pacmec-contact-form', 'pacmec_contact_form');

function pacmec_errors($atts, $content="")
{
  global $PACMEC;
  $args = \shortcode_atts([
    "title" => 'title',
    "content" => 'content',
  ], $atts);
  return \PHPStrap\Alert::leadParagraph($args['title'], $args['content'], 'danger');
}
add_shortcode('pacmec-errors', 'pacmec_errors');
