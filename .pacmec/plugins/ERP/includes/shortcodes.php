<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    Plugins
 * @category   ERP
 * @version    1.0.1
 */

function pacmec_webmail_frame($atts, $content=""){
  $args = \shortcode_atts([
    "i_i"      => false,
  ], $atts);
  get_part('components/webmail/frame', PACMEC_ERP_COMPONENTS_PATH, $args);
}
add_shortcode('webmail', 'pacmec_webmail_frame');
