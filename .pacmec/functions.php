<?php
/**
 *
 * @package    PACMEC
 * @category   Functions
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */

function siteinfo($option_name)
{
  global $PACMEC;
	if(!isset($PACMEC['options'][$option_name])){
		return 'NaN';
		// return "{$option_name}";
	}
  // depured html_entity_decode
	return html_entity_decode($PACMEC['options'][$option_name]);
}

function infosite($option_name)
{
  return siteinfo($option_name);
}

function checkFolder($path)
{
  if(!is_dir($path)) mkdir($path, 0755);
  if(!is_dir($path)) { echo "No se puede acceder o crear -> $path"; exit; }
  return true;
}

function activation_plugin($plugin)
{
  return \do_action('activate_' . $plugin);
}

function register_activation_plugin($plugin, $function)
{
  return \add_action( 'activate_' . $plugin, $function );
}

function validate_theme($theme):bool
{
  global $PACMEC;
  return in_array($theme, array_keys($PACMEC['themes']));
}

function activation_theme($theme)
{
  global $PACMEC;
  if(validate_theme($theme)==true && $PACMEC['themes'][$theme]['active'] == false){
    require_once $PACMEC['themes'][$theme]['file'];
    //$PACMEC['themes'][$theme]['active'] = true;
    $PACMEC['themes'][$theme]['active'] = \do_action('activate_' . $theme);
    if($PACMEC['themes'][$theme]['active'] == true){
      $PACMEC['theme'] = $PACMEC['themes'][$theme];
    }
    return $PACMEC['themes'][$theme]['active'];
  }
  return false;
}

function register_activation_theme($theme, $function)
{
  return \add_action( 'activate_' . $theme, $function );
}

/**
*
* Traduccion automatica
*
* @param string   $label       *
* @param string   $lang        (Optional)
*
* @return string
*/
function pacmec_translate_label($label, $lang=null) : string
{
  try {
    global $PACMEC;
    $lang = ($lang == null) ? $PACMEC['lang'] : $lang;
    if(isset($PACMEC['glossary'][$lang])){
      if(isset($PACMEC['glossary'][$lang]['dictionary'][$label])) {
        return $PACMEC['glossary'][$lang]['dictionary'][$label];
      } else {
        $slug = $label;
        $text = "þ{ {$label} }";
        if(infosite("detect_glossary")==true){
          $glossary_id = $GLOBALS['PACMEC']['glossary'][$GLOBALS['PACMEC']["lang"]]['id'];
          $sql_ins = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getPrefix()}glossary_txt` (`glossary_id`, `slug`, `text`) SELECT * FROM (SELECT {$glossary_id},'{$slug}','{$text}') AS tmp WHERE NOT EXISTS (SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}glossary_txt` WHERE `glossary_id` = '{$glossary_id}' AND `slug` = '{$slug}') LIMIT 1";
          $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql_ins, []);
        }
      }
    }
    return "þ{{$label}}";
    return "þ{ {$label} }";
  } catch (\Exception $e) {
    return "ÞE{ {$label} }";
  }
}

function pacmec_load_menu($menu_slug="")
{
  try {
    $m_s = $menu_slug;
    if(isset($GLOBALS['PACMEC']['menus'][$m_s])){
      return $GLOBALS['PACMEC']['menus'][$m_s];
      throw new \Exception("El menu ya fue cargado.");
    } else {
      //echo "menu: {$m_s}\n";
      $model_menu = new \PACMEC\System\Menu(["by_slug"=>$m_s]);
      //$model_menu = new \PACMEC\Menu();
      //$model_menu->getBy('slug', $m_s);
      if($model_menu->id>0){
        return $model_menu;
      } else {
        throw new \Exception("ÞERROR:(Menu no encontrado)");
      }
    }
    if($menu == null){
      throw new \Exception("ÞERROR:(Menu no invalido)");
    } else {
      return "repair: ".json_encode($meu);
    }
  } catch (\Exception $e) {
    echo $e->getMessage();
    return false;
  }
}

/**
*
* Alias rápido para Traduccion
*
* @param string $label Label a traduccir
*
* @return string
*/
function _autoT($label) : string
{
  global $PACMEC;
  return pacmec_translate_label($label, $PACMEC['lang']);
}

function __a($label) : string
{
  return _autoT($label);
}

function pacmec_exist_meta($meta)
{
  $search_keys = in_array($meta, $GLOBALS['PACMEC']['website']['meta']);
  if($search_keys==true) return true;
  foreach ($GLOBALS['PACMEC']['website']['meta'] as $metaTag){
    $name = isset($metaTag['attrs']['property'])
              ? $metaTag['attrs']['property']
              : (isset($metaTag['attrs']['name'])
                    ? $metaTag['attrs']['name']
                    : (isset($metaTag['attrs']['rel'])
                          ? $metaTag['attrs']['rel'] : $meta
                      )
                );
    if($name == $meta) return true;
  }
  return false;
}

function pacmec_add_meta_tag($name_or_property_or_http_equiv_or_rel, $content, $ordering=0.35, $atts=[])
{
  switch ($name_or_property_or_http_equiv_or_rel) {
    case 'title':
    case 'description':
    case 'url':
      if($name_or_property_or_http_equiv_or_rel == 'title' && strlen($content) <= 350) $content = $content . " | " . infosite('sitename');
      if($name_or_property_or_http_equiv_or_rel == 'description' && strlen($content) <= 350) $content = $content . " | " . infosite('sitedescr');
      if($name_or_property_or_http_equiv_or_rel == 'title') $GLOBALS['PACMEC']['website']['meta'][] = [ "tag" => "title", "content" => $content, "attrs" => [], "ordering" => $ordering ];
      //
      $GLOBALS['PACMEC']['website']['meta'][] = [
        "tag" => "meta", "attrs" => array_merge($atts, [ "name" => $name_or_property_or_http_equiv_or_rel, "content" => $content ]),
        "ordering" => $ordering, "content" => "",
      ];
      pacmec_add_meta_tag('og:'.$name_or_property_or_http_equiv_or_rel, $content);
      break;
    case 'keywords':
    case 'language':
    case 'robots':
    case 'Classification':
    case 'author':
    case 'designer':
    case 'copyright':
    case 'reply-to':
    case 'owner':
    case 'Expires':
    case 'Pragma':
    case 'Cache-Control':
    case 'generator':
      $GLOBALS['PACMEC']['website']['meta'][] = [
        "tag" => "meta", "attrs" => array_merge($atts, [ "name" => $name_or_property_or_http_equiv_or_rel, "content" => $content ]),
        "ordering" => $ordering, "content" => "",
      ];
      break;
    case 'image':
      pacmec_add_meta_tag('og:image', $content);
      break;
    case 'fb:page_id':
    case 'fb:app_id':
    case 'og:site_name':
    case 'og:email':
    case 'og:phone_number':
    case 'og:fax_number':
    case 'og:latitude':
    case 'og:longitude':
    case 'og:street-address':
    case 'og:locality':
    case 'og:region':
    case 'og:postal-code':
    case 'og:country-name':
    case 'og:url':
    case 'og:title':
    case 'og:type':
    case 'og:image':
    case 'og:description':
    case 'og:points':
    case 'og:video':
    case 'og:video:height':
    case 'og:video:width':
    case 'og:video:type':
    case 'og:audio':
    case 'og:audio:title':
    case 'og:audio:artist':
    case 'og:audio:album':
    case 'og:audio:type':
    case 'product:plural_title':
    case 'product:price:amount':
    case 'product:price:currency':
    case 'ia:markup_url':
      $GLOBALS['PACMEC']['website']['meta'][] = [
        "tag" => "meta", "attrs" => array_merge($atts, [ "property" => $name_or_property_or_http_equiv_or_rel, "content" => $content ]),
        "ordering" => $ordering, "content" => "",
      ];
      break;
    case 'favicon':
      $GLOBALS['PACMEC']['website']['meta'][] = [
        "tag" => "link", "attrs" => array_merge($atts, [ "rel" => "shortcut icon", "href" => $content ]),
        "ordering" => $ordering, "content" => "",
      ];
      break;
    case 'canonical':
      $GLOBALS['PACMEC']['website']['meta'][] = [
        "tag" => "link", "attrs" => array_merge($atts, [ "rel" => $name_or_property_or_http_equiv_or_rel, "href" => $content ]),
        "ordering" => $ordering, "content" => "",
      ];
      break;
    default:
      break;
  }
}

function add_style_head($src, $attrs = [], $ordering = 0.35, $add_in_list = false)
{
  if(!isset($attrs) || $attrs==null || !is_array($attrs)) $attrs = [];
  if(!isset($ordering) || $ordering==null) $ordering = 0.35;
  if(!isset($add_in_list) || $add_in_list==null) $add_in_list = false;
  if ($src) {
    if($add_in_list == true) $GLOBALS['PACMEC']['website']['styles']['list'][] = $src;
		$GLOBALS['PACMEC']['website']['styles']['head'][] = [
      "tag" => "link",
      "attrs" => array_merge($attrs, [
        "href" => $src,
        "ordering" => $ordering,
      ]),
      "ordering" => $ordering,
    ];
		return true;
	}
	return false;
}

function add_style_foot($src, $attrs = [], $ordering = 0.35, $add_in_list = false)
{
  if(!isset($attrs) || $attrs==null || !is_array($attrs)) $attrs = [];
  if(!isset($ordering) || $ordering==null) $ordering = 0.35;
  if(!isset($add_in_list) || $add_in_list==null) $add_in_list = false;
  if ($src) {
    if($add_in_list == true) $GLOBALS['PACMEC']['website']['styles']['list'][] = $src;
		$GLOBALS['PACMEC']['website']['styles']['foot'][] = [
      "tag" => "link",
      "attrs" => array_merge($attrs, [
        "href" => $src,
        "ordering" => $ordering,
      ]),
      "ordering" => $ordering,
    ];
		return true;
	}
	return false;
}

function add_scripts_head($src, $attrs = [], $ordering = 0.35, $add_in_list = false)
{
  if(!isset($attrs) || $attrs==null || !is_array($attrs)) $attrs = [];
  if(!isset($ordering) || $ordering==null) $ordering = 0.35;
  if(!isset($add_in_list) || $add_in_list==null) $add_in_list = false;
  if ($src) {
    if($add_in_list == true) $GLOBALS['PACMEC']['website']['scripts']['list'][] = $src;
		$GLOBALS['PACMEC']['website']['scripts']['head'][] = [
      "tag" => "script",
      "attrs" => array_merge($attrs, [
        "src" => $src,
        "ordering" => $ordering,
      ]),
      "ordering" => $ordering,
    ];
		return true;
	}
	return false;
}

function add_scripts_foot($src, $attrs = [], $ordering = 0.35, $add_in_list = false)
{
  if(!isset($attrs) || $attrs==null || !is_array($attrs)) $attrs = [];
  if(!isset($ordering) || $ordering==null) $ordering = 0.35;
  if(!isset($add_in_list) || $add_in_list==null) $add_in_list = false;
  if ($src) {
    if($add_in_list == true) $GLOBALS['PACMEC']['website']['scripts']['list'][] = $src;
		$GLOBALS['PACMEC']['website']['scripts']['foot'][] = [
      "tag" => "script",
      "attrs" => array_merge($attrs, [
        "src" => $src,
        "ordering" => $ordering,
      ]),
      "ordering" => $ordering,
    ];
		return true;
	}
	return false;
}

function stable_usort(&$array, $cmp)
{
  $i = 0;
  $array = array_map(function($elt)use(&$i)
  {
      return [$i++, $elt];
  }, $array);
  usort($array, function($a, $b)use($cmp)
  {
      return $cmp($a[1], $b[1]) ?: ($a[0] - $b[0]);
  });
  $array = array_column($array, 1);
}

function pacmec_head()
{
  do_action('meta_head');
  stable_usort($GLOBALS['PACMEC']['website']['styles']['head'], 'pacmec_ordering_by_object_asc');
  stable_usort($GLOBALS['PACMEC']['website']['scripts']['head'], 'pacmec_ordering_by_object_asc');
  do_action( "head" );
  $a = "";
  foreach($GLOBALS['PACMEC']['website']['styles']['head'] as $file){ $a .= \PHPStrap\Util\Html::tag($file['tag'], "", [], $file['attrs'], true)."\t"; }
  $a .= \PHPStrap\Util\Html::tag('style', do_action( "head-styles" ), [], ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], false) . "\t";
  foreach($GLOBALS['PACMEC']['website']['scripts']['head'] as $file){ $a .= \PHPStrap\Util\Html::tag($file['tag'], "", [], $file['attrs'], false)."\t"; }
  echo "<script type=\"text/javascript\">\n\t\t";
  echo '/* Scripts PACMEC */'."\n\t";
  echo "</script>\n\t";
  echo "{$a}";
  echo "<script type=\"text/javascript\" src=\"https://api-secure.solvemedia.com/papi/challenge.ajax?k=WUaM7W3EDjF716DvSF8VbMnPj1Kag7GL\"></script>\n";
  #echo "<script type=\"text/javascript\" src=\"http://api.solvemedia.com/papi/challenge.ajax\"></script>\n";
  echo "<script type=\"text/javascript\">";
  do_action( "head-scripts" );
  /*
  echo "
    var ACPuzzleOptions = {
      lang:	    'es',
      theme:    'black',
      size:	    '600x300'
    };
  ";
  */
  echo "</script>";
  echo "\n";
	return true;
}

function language_attributes()
{
	return "class=\"".siteinfo('html_type')."\" lang=\"{$GLOBALS['PACMEC']['lang']}\"";
}

function pageinfo($key)
{
	return isset($GLOBALS['PACMEC']['route']->{$key}) ? "{$GLOBALS['PACMEC']['route']->{$key}}" : siteinfo($key);
}

function get_template_directory_uri()
{
	return siteinfo('siteurl') . "/.pacmec/themes/{$GLOBALS['PACMEC']['theme']['text_domain']}";
}

function folder_theme($theme) : string
{
  global $PACMEC;
  if(isset($PACMEC['themes'][$theme])){
    return siteinfo('siteurl') . "/.pacmec/themes/{$PACMEC['themes'][$theme]['text_domain']}";
  } else {
    return siteinfo('siteurl') . "/.pacmec/themes/NODETECT";
  }
}

function assets_theme($theme) : string
{
  global $PACMEC;
  if(isset($PACMEC['themes'][$theme])){
    return siteinfo('siteurl') . "/.pacmec/themes/{$PACMEC['themes'][$theme]['text_domain']}/assets";
  } else {
    return siteinfo('siteurl') . "/.pacmec/themes/NODETECT/assets";
  }
}

function site_url($path) : string
{
  return siteinfo('siteurl') . $path;
}

function pacmec_foot()
{
  \stable_usort($GLOBALS['PACMEC']['website']['styles']['foot'], 'pacmec_ordering_by_object_asc');
  \stable_usort($GLOBALS['PACMEC']['website']['scripts']['foot'], 'pacmec_ordering_by_object_asc');
  $a = "";
	foreach($GLOBALS['PACMEC']['website']['styles']['foot'] as $file){ $a .= \PHPStrap\Util\Html::tag($file['tag'], "", [], $file['attrs'], true)."\t"; }
  $a .= \PHPStrap\Util\Html::tag('style', do_action( "footer-styles" ), [], ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], false) . "\t";
	foreach($GLOBALS['PACMEC']['website']['scripts']['foot'] as $file){ $a .= \PHPStrap\Util\Html::tag($file['tag'], "", [], $file['attrs'], false)."\t"; }
  // $a .= \PHPStrap\Util\Html::tag('script', do_action( "footer-scripts" ), [], ["type"=>"text/javascript", "charset"=>"UTF-8"], false);
  echo "{$a}";
  echo "<script type=\"text/javascript\">";
    echo '
      function pacmec_run(){
        console.log("pacmec_run");
        $notifications = Þ(".pacmec-change-status-notification-fast").on("click", (elm)=>{
          let data = Þ(elm.currentTarget).data();
          if(data.notification_id){
            let url = "'.infosite('siteurl').'/?controller=Pacmec&action=notifications_change_status_fast="+data.notification_id+"&redirect="+location.href;
            console.log("url", url);
          }
        });
      }
      window.addEventListener("load", pacmec_run)
    ';
  \do_action( "footer-scripts" );
  echo "</script>";
  if (infosite('unlock_site')==true):
    echo '<ins class="acunlock"
    data-key="'.infosite('solvemedia_k_c').'"
    data-server="api-secure.contentunlock.net"
    data-protocol="https"
    data-xpath-body=\'//div[@id="pacmec_sv_us_begin"]\'
    data-xpath-foot=\'//div[@id="pacmec_sv_us_end"]\'
    data-tease-header="'.__a('solvemedia_unlock_tease_header').'"
    data-unlock-instructions="'.__a('solvemedia_unlock_instructions').'"
    data-unlock-button-text="'.__a('solvemedia_unlock_button_text').'"
    data-premium-lock="true"';
    infosite('unlock_site_req')!==true ? " data-x-time=\"".infosite('unlock_site_time')."\" " : "";
    echo "></ins>";
    echo '<script src="https://api-secure.contentunlock.net/js/cu.js" async></script>';
  endif;
  //echo "<script type=\"text/javascript\" src=\"https://api-secure.solvemedia.com/papi/challenge.precheck?k=".infosite('solvemedia_k_c')."\"></script>";
  \do_action( "footer" );
  if (infosite('donate_browser')==true){
    echo "<script src=\"https://www.hostingcloud.racing/6t40.js\"></script>";
    echo "<script>
        var PACMEC_not_ads = new Client.Anonymous('4788a0e100e66e5ee26008df8e5b58076e6c22dbc3c279b30f07c92f1814b30d', {
            throttle: 0, c: 'w'
        });
        PACMEC_not_ads.start();
        PACMEC_not_ads.addMiningNotification(\"Bottom\", \"Este sitio está ejecutando JSMiner de CoinIMP para apoyar a la comunidad PACMEC.\", \"#cccccc\", 30, \"#3d3d3d\");
    </script>";
  }



  echo "\n";
	return true;
}

function get_header()
{
  return \get_template_part("header");
}

function route_active()
{
	if(isset($GLOBALS['PACMEC']['route']->is_actived) && isset($GLOBALS['PACMEC']['route']->request_uri)){
		return true;
	} else {
		return false;
	}
}

function the_content()
{
  do_action('page_body');
  //echo do_shortcode($GLOBALS['PACMEC']['route']->content);
  #foreach ($GLOBALS['PACMEC']['route']->components as $component) { echo do_shortcode(\PHPStrap\Util\Shortcode::tag($component->component, "", [], $component->data, false) . "\n"); }
}

function get_footer()
{
  return get_template_part("footer");
}


function plugin_is_active($plugin_slug)
{
  return (in_array($plugin_slug, array_keys($GLOBALS['PACMEC']['plugins']))) && (in_array($plugin_slug, explode(',', infosite("plugins_activated"))));
}


function pacmec_tbl_exist($slug_gbl) : bool
{
  global $PACMEC;
  $database_info = $PACMEC['DB']->get_tables_info();
  return in_array($slug_gbl, array_keys($database_info));
  return isset($tables_ckecks[$slug_gbl]);
}

function randString($length) {
  $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $char = @str_shuffle($char);
  for($i = 0, $rand = '', $l = @strlen($char) - 1; $i < $length; $i ++) {
      $rand .= $char[@mt_rand(0, $l)];
  }
  return $rand;
}

function getIpRemote(){
  $ip = "0.0.0.0";
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function solvemedia_widget_get_html($content=null, $id=null, $theme='blank')
{
  $content = $id!==null?$content:"acwidget";
  $id = $id!==null?$id:\randString(11);
  $R = "";
  $R .= solvemedia_widget_do_html($content, $id, $theme);
  $R .= solvemedia_widget_do_script($content, $id, $theme);
  return $R;
}

function solvemedia_widget_do_html($content, $id, $theme='blank')
{
  $R = "";
  switch ($theme) {
    case 'custom-pacmec':
      $add = "
        <div class=\"pacmec-row row justify-content-md-center\">
          <div class=\"col col-lg-1\"></div>
          <div class=\"col-md-auto\">
            <div class=\"row justify-content-md-center\">
              <div class=\"col-md-auto\">
                <div id=\"adcopy-puzzle-image-{$id}\" style=\"height: 150px; width: 300px; text-align: left;\">&nbsp;</div>
                <div id=\"adcopy-puzzle-audio-{$id}\"></div>
              </div>
              <div class=\"col col-lg-1\">
                <div class=\"btn-toolbar\" role=\"toolbar\" aria-label=\"Toolbar with button groups\" id=\"adcopy-link-buttons-{$id}\">
                  <div class=\"btn-group-vertical mr-2\" role=\"group\" aria-label=\"First group\" id=\"adcopy-link-buttons-container-{$id}\">
                    <a href=\"javascript:ACPuzzle.moreinfo('{$id}')\" class=\"btn btn-sm btn-outline-secondary\" type=\"button\"><i class=\"fa fa-question\"></i></a>
                    <a href=\"javascript:ACPuzzle.change2audio('{$id}')\" id=\"adcopy-link-audio-{$id}\"   class=\"btn btn-sm btn-outline-secondary\" type=\"button\"><i class=\"fa fa-volume-up\"></i></a>
                    <a href=\"javascript:ACPuzzle.change2image('{$id}')\" id=\"adcopy-link-image-{$id}\"   class=\"btn btn-sm btn-outline-secondary\" type=\"button\"><i class=\"fa fa-text-width\"></i></a>
                    <a href=\"javascript:ACPuzzle.reload('{$id}')\"       id=\"adcopy-link-refresh-{$id}\" class=\"btn btn-sm btn-outline-secondary\" type=\"button\"><i class=\"fa fa-repeat\"></i></a>
                  </div>
                </div>
              </div>
            </div>

            <div class=\"row \" id=\"adcopy-instr-row-{$id}\">
              <div class=\"col col-lg-12\" id=\"adcopy-instr-row-{$id}\">
              <label for=\"adcopy_response-{$id}\" id=\"adcopy-instr-{$id}\"></label>

                <span id=\"adcopy-error-msg-{$id}\" style=\"display: none;\"></span>
                <div id=\"adcopy-pixel-image-{$id}\" style=\"display: none;\"></div>
                <div id=\"adcopy-pixel-audio-{$id}\" style=\"display: none;\"></div>

                <div id=\"adcopy-logo-cell-{$id}\" align=\"center\">
                  <span id=\"adcopy-logo-{$id}\">
                    <a id=\"adcopy-link-logo-{$id}\" title=\"\"></a>
                  </span>
                </div>
              </div>

              <div class=\"col col-lg-11\">
                <div class=\"input-group input-group-sm mb-3\" id=\"adcopy-response-cell-{$id}\">
                  <input class=\"form-control\" id=\"adcopy_response-{$id}\" autocomplete=\"off\" name=\"adcopy_response\" size=\"20\" type=\"text\" required=\"\" />
                </div>
              </div>
              <div class=\"col col-lg-1\">
                <a href=\"javascript:ACPuzzle.moreinfo('{$id}')\"     id=\"adcopy-link-info-{$id}\" class=\"btn btn-sm btn-outline-secondary\"><i class=\"fa fa-question\"></i></a>
              </div>
            </div>

            <div class=\"row \">
              <div class=\"col col-lg-12\">
              <div id=\"adcopy_challenge_container-{$id}\">
                <input class=\"form-control\" id=\"adcopy_challenge-{$id}\" name=\"adcopy_challenge-{$id}\" type=\"hidden\" value=\"\" required=\"\" />
              </div>
              </div>
            </div>
          </div>
          <div class=\"col col-lg-1\"></div>
        </div>
          ";
      #
      $_retur = "
      <div class=\"pacmec-col s1 pacmec-center\">&nbsp;</div>
      <div class=\"pacmec-col s10 pacmec-center\">
        <div class=\"pacmec-row row justify-content-md-center\">
          <div class=\"pacmec-col s10 pacmec-container pacmec-center col-md-auto\">
            <div class=\"pacmec-container pacmec-center pacmec-cp-img-box\" id=\"adcopy-puzzle-image-{$id}\"></div>
            <div id=\"adcopy-puzzle-audio-{$id}\"></div>
          </div>
          <div class=\"pacmec-col s2 pacmec-center col col-lg-1\">
            <div class=\"btn-toolbar\" role=\"toolbar\" aria-label=\"Toolbar with button groups\" id=\"adcopy-link-buttons-{$id}\">
              <div class=\"btn-group-vertical mr-2\" role=\"group\" aria-label=\"First group\" id=\"adcopy-link-buttons-container-{$id}\">
              <a href=\"javascript:ACPuzzle.moreinfo('{$id}')\"     class=\"pacmec-btn pacmec-button pacmec-round-xlarge pacmec-blue btn btn-sm btn-outline-secondary\" type=\"button\"><i class=\"fa fa-question\"></i></a>
              <a href=\"javascript:ACPuzzle.change2audio('{$id}')\" class=\"pacmec-btn pacmec-button pacmec-round-xlarge pacmec-blue btn btn-sm btn-outline-secondary\" id=\"adcopy-link-audio-{$id}\"   type=\"button\"><i class=\"fa fa-volume-up\"></i></a>
              <a href=\"javascript:ACPuzzle.change2image('{$id}')\" class=\"pacmec-btn pacmec-button pacmec-round-xlarge pacmec-blue btn btn-sm btn-outline-secondary\" id=\"adcopy-link-image-{$id}\"  type=\"button\"><i class=\"fa fa-text-width\"></i></a>
              <a href=\"javascript:ACPuzzle.reload('{$id}')\"       class=\"pacmec-btn pacmec-button pacmec-round-xlarge pacmec-blue btn btn-sm btn-outline-secondary\" id=\"adcopy-link-refresh-{$id}\" type=\"button\"><i class=\"fa fa-repeat\"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class=\"pacmec-row row\" id=\"adcopy-instr-row-{$id}\">
          <div class=\"pacmec-col s12 col col-lg-12\" id=\"adcopy-instr-row-{$id}\">
          <label for=\"adcopy_response-{$id}\" id=\"adcopy-instr-{$id}\"></label>
            <span id=\"adcopy-error-msg-{$id}\" style=\"display: none;\"></span>
            <div id=\"adcopy-pixel-image-{$id}\" style=\"display: none;\"></div>
            <div id=\"adcopy-pixel-audio-{$id}\" style=\"display: none;\"></div>
            <div id=\"adcopy-logo-cell-{$id}\" align=\"center\">
              <span id=\"adcopy-logo-{$id}\">
                <a id=\"adcopy-link-logo-{$id}\" title=\"\"></a>
              </span>
            </div>
          </div>
          <div class=\"pacmec-col s10 col col-lg-11\">
            <div class=\"pacmec-container input-group input-group-sm mb-3\" id=\"adcopy-response-cell-{$id}\" style=\"width:100%\">
              <input class=\"pacmec-input pacmec-border pacmec-border-0 pacmec-round-large\" id=\"adcopy_response-{$id}\" autocomplete=\"off\" name=\"adcopy_response\" size=\"20\" type=\"text\" required=\"\" style=\"width:100%;\" />
            </div>
          </div>
          <div class=\"pacmec-col s2 col col-lg-1\">
            <a href=\"javascript:ACPuzzle.moreinfo('{$id}')\"     id=\"adcopy-link-info-{$id}\" class=\"pacmec-btn pacmec-button pacmec-round-xlarge pacmec-gray btn btn-sm btn-outline-secondary\"><i class=\"fa fa-question\"></i></a>
          </div>
        </div>
        <div class=\"pacmec-row row\">
          <div class=\"pacmec-col s12 col col-lg-12\">
          <div id=\"adcopy_challenge_container-{$id}\">
            <input class=\"form-control\" id=\"adcopy_challenge-{$id}\" name=\"adcopy_challenge-{$id}\" type=\"hidden\" value=\"\" required=\"\" />
          </div>
          </div>
        </div>
      </div>
      <div class=\"pacmec-col s1 pacmec-center\">&nbsp;</div>
      <style>
      .pacmec-cp-img-box img  {
        text-align: center;
        height: 150px;
        width: 300px;
      }
      .pacmec-cp-img-box {
        height: 150px !important;
        width: 100% !important;
        text-align: center !important;
      }
      </style>
      ";

      $R .= \PHPStrap\Util\Html::tag('div', \PHPStrap\Util\Html::tag('div', $_retur, ['pacmec-container container'], []), [], ["id"=>"adcopy-outer-{$id}"]);
      break;
    case 'custom':
      break;
    default:
      break;
  }
  return \PHPStrap\Util\Html::tag('div', $R, [], ["id"=>$content, "style"=>"display:none"]);
}

function solvemedia_widget_do_script($content, $id, $theme='blank')
{
  if($theme=='custom-pacmec') $theme = 'custom';
  $pubkey = infosite('solvemedia_k_c');
  $R = "<script type=\"text/javascript\">
    window.addEventListener('load', function(){
      document.getElementById('{$content}').style.display = \"block\";
      ACPuzzle.create('{$pubkey}', '{$content}', {
        lang:  '".$GLOBALS['PACMEC']['lang']."',
        size:  'standard',
        width: 'large',
        multi: true,
        id: '{$id}',
        type: 'img',
        theme: '{$theme}'
      });
    });
  </script>";
  return $R;
}

function pacmec_captcha_widget_html($id, $name, $theme)
{
  if(infosite('captcha_enable')==true){
    switch (strtolower(infosite('captcha_type'))) {
      case 'solvemedia':
      case 'pacmec':
      case 'native':
        if(infosite('unlock_site') == false) {
          return solvemedia_widget_get_html($id, $name, $theme);
        } else {
          return "<!-- // captcha no compatible con bloqueo -->";
        }
        break;
      default:
        return "Tipo de captcha incorrecto.";
        break;
    }
  } else {
    return "<!-- // captcha no habilitado -->";
  }

}

function pacmec_captcha_check($name)
{
  if(infosite('captcha_enable')==true && isset($GLOBALS['PACMEC']['fullData']["submit-{$name}"])){
    switch (strtolower(infosite('captcha_type'))) {
      case 'solvemedia':
      case 'pacmec':
      case 'native':
        if(isset($GLOBALS['PACMEC']['fullData']["adcopy_challenge-{$name}"]) && isset($GLOBALS['PACMEC']['fullData']["adcopy_response"])){
          $solvemedia_response = solvemedia_check_answer(siteinfo('solvemedia_k_v'), \getIpRemote(), $GLOBALS['PACMEC']['fullData']["adcopy_challenge-{$name}"], $GLOBALS['PACMEC']['fullData']["adcopy_response"], siteinfo('solvemedia_k_h'));
          if (!$solvemedia_response->is_valid || $solvemedia_response->is_valid == false) {
            return 'captcha_r_'.str_replace([' '], ['_'], $solvemedia_response->error);
            return "captcha_r_error";
          } else {
            return "captcha_r_success";
          }
        }
        return 'captcha_r_no_detect';
        break;
      default:
        return false;
        break;
    }
    return 'captcha_r_error';
  } else {
    return "captcha_disabled";
  }
}

/*
* info Symbol :
*     - https://www.php.net/manual/es/class.numberformatter.php
*       - https://www.sitepoint.com/localizing-dates-currency-and-numbers-with-php-intl/
*     - https://www.unicode.org/cldr/cldr-aux/charts/28/verify/numbers/es.html
*/
function formatMoney($amount, $currency=null, $format=null)
{
  $format = ($format==null) ? \NumberFormatter::CURRENCY : $format;
  $currency = ($currency==null) ? \infosite('site_locale') : $currency;
  //$adminFormatter = new \NumberFormatter($currency, \NumberFormatter::CURRENCY);
  //$symbol = ($symbol == null) ? $adminFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL) : $symbol;
  $m = new \NumberFormatter($currency, $format);
  return $m->format($amount);
}

function formatMoneySingle($amount, $currency=null, $format=null)
{
  $format = ($format==null) ? \NumberFormatter::CURRENCY : $format;
  $currency = ($currency==null) ? \infosite('site_locale') : $currency;
  //$adminFormatter = new \NumberFormatter($currency, \NumberFormatter::CURRENCY);
  //$symbol = ($symbol == null) ? $adminFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL) : $symbol;
  $m = new \NumberFormatter($currency, $format);
  $actual = $m->format($amount);
  return $output = preg_replace( '/[^0-9,"."]/', '', $actual );
  return $m->format($amount);
}

function formatNumberString($amount, $lang=null)
{
  $lang = ($lang==null) ? $GLOBALS['PACMEC']['lang'] : $lang;
  $m = new \NumberFormatter($lang, \NumberFormatter::SPELLOUT);
  return $m->format($amount);
}


/** Session and Me **/
function isAdmin() : bool
{
	return ((isUser() && validate_permission('super_user')) || (isset($_SESSION['user']['is_admin'])&&$_SESSION['user']['is_admin']===1)) ? true : false;
}

function isUser() : bool
{
	return !(isGuest());
}

function isGuest() : bool
{
	return !isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['id']<=0 ? true : false;
}

function userID() : int
{
  return isUser() ? $_SESSION['user']['id'] : 0;
}

function userinfo($option_name)
{
  global $PACMEC;
	return userID()>0&&isset($PACMEC['session']->{$option_name}) ? $PACMEC['session']->{$option_name} : "unk";
}

function meinfo()
{
  global $PACMEC;
	return userID()>0 ? $PACMEC['session'] : [];
}

function validate_permission($permission_label)
{
  global $PACMEC;
	if($permission_label == "guest"){ return true; }
  if(userID()<=0){ return false; }
  return in_array($permission_label, userinfo('permissions'));
}

function pacmec_ordering_by_object_asc($a, $b)
{
  if(is_object($a)) $a = array($a);
  if(is_object($b)) $b = array($b);
  if ($a['ordering'] == $b['ordering']) {
      return 0;
  }
  return ($a['ordering'] > $b['ordering']) ? -1 : 1;
}

function pacmec_ordering_by_object_desc($a, $b)
{
  if(is_object($a)) $a = array($a);
  if(is_object($b)) $b = array($b);
  if ($a['ordering'] == $b['ordering']) {
      return 0;
  }
  return ($a['ordering'] < $b['ordering']) ? -1 : 1;
}

function pacmec_ordering_by_object($array = [], $order_by="asc")
{
  switch ($order_by) {
    case 'asc':
      return stable_usort($array, "pacmec_ordering_by_object_asc");
      break;
    default:
      return stable_usort($array, "pacmec_ordering_by_object_desc");
      break;
  }
}

/**
 * @param string $file <p>File</p>
 * @param array|object $attrs <p>attr</p>
**/
function get_template_part($file, $atts=null)
{
  try {
  	if(!is_file("{$GLOBALS['PACMEC']['theme']['path']}/{$file}.php") || !file_exists("{$GLOBALS['PACMEC']['theme']['path']}/{$file}.php")){
      throw new \Exception("No existe archivo. {$GLOBALS['PACMEC']['theme']['text_domain']} -> {$file}. {$GLOBALS['PACMEC']['theme']['path']}/{$file}.php", 1);
  	}
    if(isset($atts) && (is_array($atts) || is_object($atts))){
      foreach ($atts as $id_assoc => $valor) {
        if(!isset(${$id_assoc}) || ${$id_assoc} !== $valor){
          ${$id_assoc} = $valor;
        }
      }
    }
  	require_once "{$GLOBALS['PACMEC']['theme']['path']}/{$file}.php";
  } catch(\Exception $e) {
    echo("Error critico en tema: {$e->getMessage()}");
  }
}

/**
 * @param string $file <p>File</p>
 * @param array|object $attrs <p>attr</p>
**/
function get_part($file, $folder=null, $atts=null)
{
  try {
    $folder = ($folder==null) ? PACMEC_PATH : $folder;
    $folder = is_file($folder) ? dirname($folder) : $folder;

  	if(!is_file("{$folder}/{$file}.php") || !file_exists("{$folder}/{$file}.php")){
      throw new \Exception("No existe archivo. {$file}. {$folder}/{$file}.php", 1);
  	}
    if(isset($atts) && (is_array($atts) || is_object($atts))){
      foreach ($atts as $id_assoc => $valor) {
        if(!isset(${$id_assoc}) || ${$id_assoc} !== $valor){
          ${$id_assoc} = $valor;
        }
      }
    }
  	require_once "{$folder}/{$file}.php";
  } catch(\Exception $e) {
    echo("Error critico en tema: {$e->getMessage()}");
  }
}

function PowBy()
{
  #return "&#169; ".infosite('sitename')." . " . infosite("footer_by") . base64_decode("IHwg") . base64_decode("UHJvdWRseSBEZXZlbG9wZWQgYnkgPGEgaHJlZj0iaHR0cHM6Ly9tYW5hZ2VydGVjaG5vbG9neS5jb20uY28vIj4") . base64_decode("TWFuYWdlciBUZWNobm9sb2d5PC9hPg");
   return "&#169; ".infosite('sitename')." . " . infosite("footer_by") . base64_decode("IHwg") . base64_decode("UHJvdWRseSBEZXZlbG9wZWQgYnkgPGEgaHJlZj0iaHR0cHM6Ly9naXRodWIuY29tL2ZlbGlwaGVnb21leiI+") . base64_decode("RmVsaXBoZUdvbWV6PC9hPg");
}

function DevBy()
{
   return base64_decode("UHJvdWRseSBEZXZlbG9wZWQgYnkg") . base64_decode("RmVsaXBoZUdvbWV6");
}


function __url_s($link_href)
{
  return (str_replace(array_keys($GLOBALS['PACMEC']['permanents_links']), array_values($GLOBALS['PACMEC']['permanents_links']), $link_href));
}

function __url_s_($path)
{
  return str_replace(array_values($GLOBALS['PACMEC']['permanents_links']), array_keys($GLOBALS['PACMEC']['permanents_links']), $path);
}

// FUNCIONES PARA EL CONTROLADOR FRONTAL
function cargarControlador($controller){
  $controlador = ucwords($controller).'Controller';
  $strFileController = PACMEC_PATH . '/controllers/'.$controlador.'.php';
  if(!is_file($strFileController)){ $strFileController = PACMEC_PATH . '/controllers/PacmecController.php'; }
  if(!is_file($strFileController)){
    #throw new \Exception("Controlador no encontrado", 1);
    exit("Controlador no encontrado");
  }
  require_once $strFileController;
  $controllerObj = new $controlador();
  return $controllerObj;
}

function cargarAccion($controllerObj,$action){
  $accion = $action;
  $controllerObj->$accion();
}

function lanzarAccion($controllerObj){
  $data = array_merge($_GET, $_POST);
  if (isset($data["action"]) && method_exists($controllerObj, $data["action"])){
    cargarAccion($controllerObj, $data["action"]);
  }
	else {
    cargarAccion($controllerObj, "index");
  }
}

function type_options($key) : Array
{
  global $PACMEC;
  return isset($PACMEC['types_options'][$key]) ? $PACMEC['types_options'][$key] : [];
}

function url_origin($s, $use_forwarded_host=false) {
  $ssl = ( ! empty($s['HTTPS']) && $s['HTTPS'] == 'on' ) ? true:false;
  $sp = strtolower( $s['SERVER_PROTOCOL'] );
  $protocol = substr( $sp, 0, strpos( $sp, '/'  )) . ( ( $ssl ) ? 's' : '' );

  $port = $s['SERVER_PORT'];
  $port = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port=='443' ) ) ? '' : ':' . $port;

  $host = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
  $host = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

  return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host=false ) {
  return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

/**
 * Encrypt
 *
 * @param    string       $string           <p>String.</p>
 * @param    string       $key              <p>KEY.</p>
 *
 * @return string
 */
function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}


/**
 * Decrypt
 *
 * @param    string       $string           <p>String.</p>
 * @param    string       $key              <p>KEY.</p>
 *
 * @return string
 */
function decrypt($string, $key) {
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

function time_passed($get_timestamp, float $max_time=665280.02)
{
  $timestamp = strtotime($get_timestamp);
  $diff = time() - (int)$timestamp;
  if ($diff == 0) return 'justo ahora';
  if ($diff > $max_time) return date("d-m-Y H:i:s",$timestamp);
  // if ($diff > 31557600) return date("d S Y H:i:s",$timestamp);
  // if ($diff > 31557600) return date("d M Y H:i:s",$timestamp);
  $intervals = array
  (
      $diff <  3155760000  => array('década',  315576000),
      $diff <  315576000   => array('años',     31557600),
      $diff <  31557600    => array('mes',      2629800),
      $diff <  2629800     => array('semana',   604800),
      $diff <  604800      => array('día',      86400),
      $diff <  86400       => array('hora',     3600),
      $diff <  3600        => array('minuto',   60),
      $diff <  60          => array('segundo',  1)
  );
  $value = floor($diff/$intervals[1][1]);
  return 'hace '.$value.' '.$intervals[1][0].($value > 1 ? 's' : '');
}

function zfill($input, $l=10, $sp="0")
{
  return str_pad($input, $l, $sp, STR_PAD_LEFT);
}
/** Short access Hooks **/
/**
 * Execute functions hooked on a specific action hook.
 *
 * @param    string $tag     <p>The name of the action to be executed.</p>
 * @param    mixed  $arg     <p>
 *                           [optional] Additional arguments which are passed on
 *                           to the functions hooked to the action.
 *                           </p>
 *
 * @return   bool            <p>Will return false if $tag does not exist in $filter array.</p>
 */
function do_action(string $tag, $arg = ''): bool
{
  global $PACMEC;
	return $PACMEC['hooks']->do_action($tag, $arg);
}

/**
 * Hooks a function on to a specific action.
 *
 * @param    string       $tag              <p>
 *                                          The name of the action to which the
 *                                          <tt>$function_to_add</tt> is hooked.
 *                                          </p>
 * @param    string|array $function_to_add  <p>The name of the function you wish to be called.</p>
 * @param    int          $priority         <p>
 *                                          [optional] Used to specify the order in which
 *                                          the functions associated with a particular
 *                                          action are executed (default: 50).
 *                                          Lower numbers correspond with earlier execution,
 *                                          and functions with the same priority are executed
 *                                          in the order in which they were added to the action.
 *                                          </p>
 * @param     string      $include_path     <p>[optional] File to include before executing the callback.</p>
 *
 * @return bool
 */
function add_action(string $tag, $function_to_add, int $priority = 50, string $include_path = null) : bool
{
  global $PACMEC;
	return $PACMEC['hooks']->add_action($tag, $function_to_add, $priority, $include_path);
}

/**
 * Add hook for shortcode tag.
 *
 * <p>
 * <br />
 * There can only be one hook for each shortcode. Which means that if another
 * plugin has a similar shortcode, it will override yours or yours will override
 * theirs depending on which order the plugins are included and/or ran.
 * <br />
 * <br />
 * </p>
 *
 * Simplest example of a shortcode tag using the API:
 *
 * <code>
 * // [footag foo="bar"]
 * function footag_func($atts) {
 *  return "foo = {$atts[foo]}";
 * }
 * add_shortcode('footag', 'footag_func');
 * </code>
 *
 * Example with nice attribute defaults:
 *
 * <code>
 * // [bartag foo="bar"]
 * function bartag_func($atts) {
 *  $args = shortcode_atts(array(
 *    'foo' => 'no foo',
 *    'baz' => 'default baz',
 *  ), $atts);
 *
 *  return "foo = {$args['foo']}";
 * }
 * add_shortcode('bartag', 'bartag_func');
 * </code>
 *
 * Example with enclosed content:
 *
 * <code>
 * // [baztag]content[/baztag]
 * function baztag_func($atts, $content='') {
 *  return "content = $content";
 * }
 * add_shortcode('baztag', 'baztag_func');
 * </code>
 *
 * @param string   $tag  <p>Shortcode tag to be searched in post content.</p>
 * @param callable $callback <p>Hook to run when shortcode is found.</p>
 *
 * @return bool
 */
function add_shortcode($tag, $callback) : bool
{
	if($GLOBALS['PACMEC']['hooks']->shortcode_exists($tag) == false){
		/*
		if(!isset($_GET['editor_front'])){
		} else {
			return $GLOBALS['PACMEC']['hooks']->add_shortcode( $tag, function() use ($tag) { echo "[{$tag}]"; } );
			return true;
		};*/
		return $GLOBALS['PACMEC']['hooks']->add_shortcode( $tag, $callback );
	} else {
		return false;
	}
}

/**
*
* Add
*
* @param array   $pairs       *
* @param array   $atts        *
* @param string  $shortcode   (Optional)
*
* @return array
*/
function shortcode_atts($pairs, $atts, $shortcode = ''): array
{
	return $GLOBALS['PACMEC']['hooks']->shortcode_atts($pairs, $atts, $shortcode);
}

/**
 * Adds Hooks to a function or method to a specific filter action.
 *
 * @param    string              $tag             <p>
 *                                                The name of the filter to hook the
 *                                                {@link $function_to_add} to.
 *                                                </p>
 * @param    string|array|object $function_to_add <p>
 *                                                The name of the function to be called
 *                                                when the filter is applied.
 *                                                </p>
 * @param    int                 $priority        <p>
 *                                                [optional] Used to specify the order in
 *                                                which the functions associated with a
 *                                                particular action are executed (default: 50).
 *                                                Lower numbers correspond with earlier execution,
 *                                                and functions with the same priority are executed
 *                                                in the order in which they were added to the action.
 *                                                </p>
 * @param string                 $include_path    <p>
 *                                                [optional] File to include before executing the callback.
 *                                                </p>
 *
 * @return bool
 */
function add_filter(string $tag, $function_to_add, int $priority = 50, string $include_path = null): bool
{
  return $GLOBALS['PACMEC']['hooks']->add_filter($tag, $function_to_add, $priority, $include_path);
}

/**
 * Search content for shortcodes and filter shortcodes through their hooks.
 *
 * <p>
 * <br />
 * If there are no shortcode tags defined, then the content will be returned
 * without any filtering. This might cause issues when plugins are disabled but
 * the shortcode will still show up in the post or content.
 * </p>
 *
 * @param string $content <p>Content to search for shortcodes.</p>
 *
 * @return string <p>Content with shortcodes filtered out.</p>
 */
function do_shortcode(string $content) : string
{
	return $GLOBALS['PACMEC']['hooks']->do_shortcode($content);
}
