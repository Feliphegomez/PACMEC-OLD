<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Plugins
 * @category   ERP
 * @version    1.0.1
 */
$meinfo = meinfo();
$index = isset($i_i) && !empty($i_i) ? $i_i : (isset($_GET['i_i']) && !empty($_GET['i_i']) ? $_GET['i_i'] : 0);
$box = isset($meinfo->emails_boxes[$index]) ? $meinfo->emails_boxes[$index] : false;

?>
<style>
.frame-webmail {
  width: 100%;
  min-height: calc(80vh);
}
</style>
<div class="main-page">
  <h2 class="title1"><?= _autoT('webmail'); ?> - <?= ($box->label); ?> | <?= $box->user; ?></h2>
  <div class="blank-page widget-shadow scroll" id="style-2 div1">
    <?php
      if ($box !== false) {
        if($box->actived == 1):
          $link_frame = "/webmail/?postlogin&_user={$box->user}&_pass={$box->pass}&_action=login";
          ?>
          <iframe id="frame2" class="frame-webmail" frameborder="" src='/webmail/?_task=login&_action=login'></iframe>
          <script>
          setTimeout(() => {
          	$("#frame2").attr('src', '<?= $link_frame; ?>');
          	setTimeout(() => {
              // $("#frame2").attr('src', '/webmail/?_task=switch_skin&_action=switch_mobile');
          		$("#frame2").attr('class', 'frame-webmail');
          	}, 500);
          }, 250);
          </script>
        <?php
        else:
          echo \PHPStrap\Util\Html::tag('p', _autoT('webmail_box_disabled'));
        endif;
      }
    ?>
  </div>
</div>
