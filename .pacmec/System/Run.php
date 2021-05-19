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

namespace PACMEC\System;

#use \PACMEC\System\Route as Route;

class Run
{
  public function __construct()
  {
    require_once PACMEC_PATH . '/functions.php';
    Self::pacmec_create_globals_vars();
    Self::pacmec_init_setup();
    Self::pacmec_init_options();
    Self::pacmec_init_files_includes();
    Self::pacmec_run_session();
    Self::pacmec_init_session();
    Self::pacmec_init_gateways();
    Self::pacmec_init_plugins_actives();
    Self::pacmec_init_themes();
    Self::pacmec_init_route();
    Self::pacmec_validate_route();
    Self::pacmec_theme_check();
    Self::pacmec_assets_globals();
    Self::pacmec_run_ui();
  }

  public function pacmec_run_session()
  {
    \session_set_save_handler(new \PACMEC\System\SysSession(), true);
    if(Self::is_session_started() === FALSE || \session_id() === "") \session_start();
  }

  public function pacmec_init_session()
  {
    global $PACMEC;
    //$_SESSION['user']['id'] = 1;
    $PACMEC['session'] = new \PACMEC\System\Session();
  }

  public static function pacmec_theme_check()
  {
    // VALIDAR QUE ESTE ACTIVO EL TEMA
    if(isset($GLOBALS['PACMEC']['theme']['active']) && $GLOBALS['PACMEC']['theme']['active'] == true){
    } else {
      exit("El tema no existe.");
    }
  }

  public static function pacmec_assets_globals()
  {
    add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/css/pacmec.css"."?&cache=".rand(),  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);
    // add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/css/plugins.css", ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 0.99, false);
    add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/js/plugins.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);
    // add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/sweetalert2/sweetalert2.all.min.js",    ["type"=>"text/javascript", "charset"=>"UTF-8"], 0, false);
    // add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/vue/vue.min.js",    ["type"=>"text/javascript", "charset"=>"UTF-8"], 0, false);
    // add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/vue/vue-router.js",    ["type"=>"text/javascript", "charset"=>"UTF-8"], 0, false);
    // add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/js/sdk.js"."?&cache=".rand(),   ["type"=>"text/javascript", "charset"=>"UTF-8"], 0, false);

    add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/dist/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);
    add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);

    #add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/dist/Metro-UI-CSS/build/css/metro-all.min.css",  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);
    #add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/Metro-UI-CSS/build/js/metro.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);

    //add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/ckeditor/ckeditor.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);

    if (infosite('grapesjs_enable')==true) {
      add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/dist/grapesjs-0.17.3/css/grapes.min.css"."?&cache=".rand(),  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);
      add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/dist/grapesjs-0.17.3/plugins/grapesjs-preset-webpage/dist/grapesjs-preset-webpage.min.css",  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);
      add_style_head(siteinfo('siteurl')   . "/.pacmec/assets/dist/grapesjs-0.17.3/plugins/grapesjs-preset-newsletter/dist/grapesjs-preset-newsletter.css",  ["rel"=>"stylesheet", "type"=>"text/css", "charset"=>"UTF-8"], 1, false);

      add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/grapesjs-0.17.3/grapes.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);
      add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/grapesjs-0.17.3/plugins/grapesjs-preset-webpage/dist/grapesjs-preset-webpage.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);
      add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/grapesjs-0.17.3/plugins/grapesjs-preset-newsletter/dist/grapesjs-preset-newsletter.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);
      //add_scripts_head(siteinfo('siteurl') . "/.pacmec/assets/dist/grapesjs-0.17.3/plugins/grapesjs-plugin-ckeditor/dist/grapesjs-plugin-ckeditor.min.js",   ["type"=>"text/javascript", "charset"=>"UTF-8"], 1, false);

    }
  }

  public static function pacmec_run_ui()
  {
    if(
      isset($GLOBALS['PACMEC']['theme']['path'])
      && is_file($GLOBALS['PACMEC']['theme']['path'] . '/index.php')
      && file_exists($GLOBALS['PACMEC']['theme']['path'] . '/index.php')
    )
    {
      $data = $GLOBALS['PACMEC']['fullData'];
      if(isset($data['controller']))
      {
        $controllerObj = \cargarControlador($data["controller"]);
        lanzarAccion($controllerObj);
      }
      else
      {
        require_once $GLOBALS['PACMEC']['theme']['path'] . '/index.php';
      }
    } else {
      throw new \Exception("Hubo un problema al ejecutar la Interfas de Usuario. {$GLOBALS['PACMEC']['theme']['text_domain']} -> index.php]", 1);
      ;
      exit;
    }
  }

  public static function pacmec_validate_route()
  {
    //echo "pacmec_validate_route";
    if($GLOBALS['PACMEC']['route']->theme == null) $GLOBALS['PACMEC']['route']->theme = siteinfo('theme_default');
    add_action('page_title', function(){ if(isset($GLOBALS['PACMEC']['route']->id)){ echo (pageinfo('page_title') !== 'NaN') ? _autoT(pageinfo('page_title')) : _autoT(pageinfo('title')); } });
    add_action('page_description', function(){ if(isset($GLOBALS['PACMEC']['route']->id)){ echo (pageinfo('page_description') !== 'NaN') ? pageinfo('page_description') : _autoT(pageinfo('description')); } });
    add_action('page_body', function(){
    	if(isset($GLOBALS['PACMEC']['route']->request_uri) && $GLOBALS['PACMEC']['route']->request_uri !== ""){
    		$GLOBALS['PACMEC']['route']->content = do_shortcode($GLOBALS['PACMEC']['route']->content);
        echo $GLOBALS['PACMEC']['route']->content;
    	}
    	else {
    		echo do_shortcode(
    			errorHtml("Lo sentimos, no se encontro el archivo o página.", "Ruta no encontrada")
    		);
    	}
    });
    /*
    add_action('head', function(){
      $r = "\t<script type=\"text/javascript\" charset=\"UTF-8\">";
      $r .= "
        window.mtAsyncInit = function() {
          PACMEC.init({
        		api_server : 'https://pacmec.managertechnology.com.co',
        		appId      : 'managertechnology',
        		token      : 'pacmec',
        		Wmode      : '".infosite('wco_mode')."',
        		Wversion      : 'v1',
        	});
        };";
      $r .= "
      </script>";
      echo $r;
      //Wmode      : '<?= WCO_MODE; ?',
    });*/
  }

  public static function pacmec_init_themes()
  {
    global $PACMEC;
    $path_theme = null;
    $path = PACMEC_PATH."/themes";
    $theme_def = \siteinfo('theme_default');

    if(is_dir("{$path}/{$theme_def}")){
      $path_theme = "{$path}/{$theme_def}/{$theme_def}.php";
    } else if(is_file("{$path}/{$theme_def}.php")){
      $path_theme = "{$path}/{$theme_def}.php";
    }

    if(is_file($path_theme)){
      $file_info = Self::validate_file($path_theme);
      if(isset($file_info['theme_name'])){
        $PACMEC['themes'][$file_info['text_domain']] = $file_info;
        $PACMEC['themes'][$file_info['text_domain']]['active'] = false;
        $PACMEC['themes'][$file_info['text_domain']]['path'] = dirname($path_theme);
        $PACMEC['themes'][$file_info['text_domain']]['file'] = ($path_theme);
        if(is_file($PACMEC['themes'][$file_info['text_domain']]['file'])){
    			//require_once $path_theme;
          //\activation_plugin($file_info['text_domain']);
    		}
      }
      foreach(\glob($path."/*/*") as $file_path){
        $dirname = dirname($file_path);
        $name = str_replace(['.php'], [''], basename($file_path));
        $file_info = Self::validate_file($file_path);
        if(isset($file_info['theme_name'])){
          $PACMEC['themes'][$file_info['text_domain']] = $file_info;
          $PACMEC['themes'][$file_info['text_domain']]['active'] = false;
          $PACMEC['themes'][$file_info['text_domain']]['path'] = $dirname;
          $PACMEC['themes'][$file_info['text_domain']]['file'] = ($file_path);
        }
      }
      /*
      foreach(\glob($path."/*.php") as $file_path){
        $dirname = dirname($file_path);
        //$name = str_replace(['.php'], [''], basename($file_path));
        $file_info = Self::validate_file($file_path);
        if(isset($file_info['theme_name'])){
          $PACMEC['themes'][$file_info['text_domain']] = $file_info;
          $PACMEC['themes'][$file_info['text_domain']]['active'] = false;
          $PACMEC['themes'][$file_info['text_domain']]['path'] = $dirname;
          $PACMEC['themes'][$file_info['text_domain']]['file'] = ($file_path);
        }
      }*/
    }
    else {
      /*\PACMEC\System\Alert::addAlert([
        "type"        => "error",
        "plugin"      => "system",
        "message"     => "El tema {$path_theme}, no tiene el formato correcto.\n",
        "actions"  => [
          [
            "name" => "themes-errors",
            "theme" => $path_theme,
            "slug" => "/?c=admin&a=themes&p={$path_theme}&tab=errors_logs",
            "text" => "Ups error"
          ]
        ],
      ]);*/
      throw new \Exception("No existe el tema principal [{$theme_def}]. path: {$path_theme}", 1);
      exit();
    }
  }

  public function pacmec_init_gateways()
  {
    global $PACMEC;
    $PACMEC['gateways']['payments'] = new \PACMEC\System\PaymentGateways();
  }

  public static function pacmec_init_plugins_actives()
  {
    global $PACMEC;
    $path = PACMEC_PATH."/plugins";
    $plugins_activateds = explode(',', \siteinfo('plugins_activated'));
    foreach($plugins_activateds as $p){
      $path_plugin = null;
      if(is_dir("{$path}/{$p}")){
        $path_plugin = "{$path}/{$p}/{$p}.php";
      } else if(is_file("{$path}/{$p}.php")){
        $path_plugin = "{$path}/{$p}.php";
      }
      if(is_file($path_plugin)){
        $file_info = Self::validate_file($path_plugin);
        if(isset($file_info['plugin_name'])){
          $PACMEC['plugins'][$file_info['text_domain']] = $file_info;
          $PACMEC['plugins'][$file_info['text_domain']]['active'] = true;
          $PACMEC['plugins'][$file_info['text_domain']]['path'] = dirname($path_plugin);
          $PACMEC['plugins'][$file_info['text_domain']]['file'] = ($path_plugin);
          if(is_file($PACMEC['plugins'][$file_info['text_domain']]['file'])){
      			require_once $path_plugin;
            \activation_plugin($file_info['text_domain']);
      		}
        } else {
          \PACMEC\System\Alert::addAlert([
            "type"        => "error",
            "plugin"      => "system",
            "message"     => "El plugin {$p}, no tiene el formato correcto.\n",
            "actions"  => [
              [
                "name" => "plugins-errors",
                "plugin" => $p,
                "slug" => "/?c=admin&a=plugins&p={$p}&tab=errors_logs",
                "text" => "Ups error"
              ]
            ],
          ]);
        }
      } else {
        \PACMEC\System\Alert::addAlert([
          "type"        => "error",
          "plugin"      => "system",
          "message"     => "Hay problemas para cargar un plugin {$p}, quiere hacer una revision?\n",
          "actions"  => [
            [
              "name" => "plugins-errors",
              "plugin" => $p,
              "slug" => "/?c=admin&a=plugins&p={$p}&tab=errors_logs",
              "text" => "Ups error"
            ]
          ],
        ]);
      }
    }
  }

  public static function pacmec_init_files_includes()
  {
    try {
      require_once PACMEC_PATH . '/.prv/solvemedialib.php';
      require_once PACMEC_PATH . '/actions.php';
      require_once PACMEC_PATH . '/shortcodes.php';
    } catch (\Exception $e) {
      exit('Error en los archivos principales');
    }
  }

  public static function is_session_started() : bool
  {
      if ( \php_sapi_name() !== 'cli' ) {
          if ( \version_compare(\phpversion(), '5.4.0', '>=') ) {
              return \session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
          } else {
              return \session_id() === '' ? FALSE : TRUE;
          }
      }
      return FALSE;
  }

  /**
  * Creacion de variables globales
  *
  * @author FelipheGomez <feliphegomez@gmail.com>
  */
  public static function pacmec_create_globals_vars() : void
  {
    global $PACMEC;
    if($_SERVER['SERVER_NAME'] == PACMEC_HOST)
    {
      $PACMEC['hooks'] = \PACMEC\System\Hooks::getInstance();
      $PACMEC['DB'] = \PACMEC\System\DB::conexion();
      $PACMEC['ip'] = Self::get_ip_address();
      $PACMEC['host'] = $_SERVER['SERVER_NAME'];
      $PACMEC['fullData'] = Self::get_data_full();
      $PACMEC['lang'] = Self::get_detect_lang();
      $PACMEC['path_orig'] = \str_replace([PACMEC_HOST], '', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI']);
      $PACMEC['path'] = \strtok($PACMEC['path_orig'], '?');
      $PACMEC['glossary'] = Self::get_langs_http();
      $PACMEC['theme'] = [];
      $PACMEC['plugins'] = [];
      $PACMEC['options'] = [];
      $PACMEC['alerts'] = [];
      $PACMEC['total_records'] = $PACMEC['DB']->getTotalRows();
      //$PACMEC['route'] = new \PACMEC\System\Route();
      if(!empty($PACMEC['fullData']['lang'])){
        $PACMEC['lang'] = in_array($PACMEC['fullData']['lang'], \array_keys($PACMEC['glossary'])) ? $PACMEC['fullData']['lang'] : (!empty($_COOKIE['language']) ? $_COOKIE['language'] : PACMEC_LANG_DEF);
        $PACMEC['glossary'] = Self::get_langs_http();
      }
    } else {
      exit("Servidor no autorizado. ");
    }
  }

  public static function pacmec_load_plugins($path)
  {
    if(\checkFolder($path))
    {
    	$folder_JSON = \php_file_tree_dir_JSON_exts($path, true, [], true, 0, 1);
      echo json_encode($folder_JSON)."\n";
    	foreach($folder_JSON as $file){
    		if(is_dir($file->link)){
    			$PACMEC = array_merge($PACMEC, Self::pacmec_load_plugins($file->link));
    		} else {
    			$type = Self::validate_type_file($file->link);
    			if($type == "plugin"){
    				$plugins_activated = siteinfo("plugins_activated");
    				$info = Self::validate_file($file->link);
    				if(isset($info['plugin_name'])){
    					$info['active'] = false;
    					$info['text_domain'] = (isset($info['text_domain']) ? $info['text_domain'] : str_replace(['  ',' '], ['-','-'], $info['plugin_name']));
    					$PACMEC[$info['text_domain']] = $info;
    				}
    			}
    		}
    	}
    }
  }
  public static function pacmec_init_setup()
  {
    try {
      global $PACMEC;
      $sql = "SELECT * from `INFORMATION_SCHEMA`.`TABLES` where (`information_schema`.`TABLES`.`TABLE_SCHEMA` = database())";
      $database_info = $PACMEC['DB']->get_tables_info();
      $tables_ckecks = [
        'categories'         => false,
        'comments'           => false,
        'emails_templates'   => false,
        'geo_addresses'      => false,
        'geo_countries'      => false,
        'geo_cities'         => false,
        'glossary'           => false,
        'glossary_txt'       => false,
        'menus'              => false,
        'menus_elements'     => false,
        'notifications'      => false,
        'options'            => false,
        'permanents_links'   => false,
        'permissions'        => false,
        'permissions_group'  => false,
        'permissions_items'  => false,
        'permissions_users'  => false,
        'routes'             => false,
        'sessions'           => false,
        'users'              => false,
        'types_options'      => false,
      ];
      foreach ($database_info as $slug_gbl => $tbl) {
        if(isset($tables_ckecks[$slug_gbl])) {
          $tables_ckecks[$slug_gbl] = true;
        }
      }
      if(\in_array(false, \array_values($tables_ckecks)) == true) throw new \Exception("Faltan tablas: ".\json_encode($tables_ckecks, JSON_PRETTY_PRINT)."\n", 1);
    } catch (\Exception $e) {
      exit($e->getMessage());
    }
  }

  public static function pacmec_init_options()
  {
    try {
      global $PACMEC;
      foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM `{$PACMEC['DB']->getTableName('options')}` WHERE `host` IN (?,?) ORDER BY `host` ASC, `option_name` ASC ", ['*', $PACMEC['host']]) as $option){
        $PACMEC['options'][$option->option_name] = Self::pacmec_parse_value($option->option_value);
      }
      foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM `{$PACMEC['DB']->getTableName('permanents_links')}` ", []) as $option){
        $PACMEC['permanents_links'][$option->slug] = urlencode(__a($option->result));
      }
      foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM `{$PACMEC['DB']->getTableName('types_options')}` ORDER BY `name` ASC", []) as $option){
        if(!isset($PACMEC['types_options'][$option->type])){
          $PACMEC['types_options'][$option->type] = [];
        }
        $option->name = ($option->autot==1) ? __a($option->name) : $option->name;
        $PACMEC['types_options'][$option->type][] = $option;
      }
      foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM `{$PACMEC['DB']->getTableName('geo_countries')}` ORDER BY `name` ASC ", []) as $option){
        // $PACMEC['geo']['countries'][$option->code] = $option;
        $PACMEC['geo']['countries'][] = $option;
      }
      foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM `{$PACMEC['DB']->getTableName('geo_cities')}` ORDER BY `name` ASC ", []) as $option){
        if(!isset($PACMEC['geo']['cities'][$option->country_code])) $PACMEC['geo']['cities'][$option->country_code] = [];
        $PACMEC['geo']['cities'][$option->country_code][] = $option;
      }
      $options_ckecks = [
        'siteurl'=>false,
        'sitename'=>false,
        'sitedescr'=>false,
        'sitelogo'=>false,
        'enable_ssl'=>false,
        'charset'=>false,
        'lang_default'=>false,
        'html_type'=>false,
        'homeurl'=>false,
        'footer_by'=>false,
        'plugins_activated'=>false,
        'theme_default'=>false,
        'format_time_s'=>false,
        'format_date_s'=>false,
        'author'=>false,
        'business_email'=>false,
        'business_phone_number'=>false,
        'business_address'=>false,
        'business_hours'=>false,
        'detect_glossary'=>false,
        'unlock_site'=>false,
        'unlock_site_req'=>false,
        'unlock_site_time'=>false,
        'captcha_enable'=>false,
        'captcha_type'=>false,
        'site_locale'=>false,
        'site_currency'=>false,
        'donate_browser'=>false,
      ];
      foreach ($options_ckecks as $key => $value) { if(\infosite($key) !== 'NaN' && !empty($key)) $options_ckecks[$key] = true; }
      if(in_array(false, array_values($options_ckecks)) == true) throw new \Exception("Error en las opciones del sitio.".\json_encode($options_ckecks, JSON_PRETTY_PRINT)."\n", 1);
      setlocale(LC_ALL, \infosite('site_locale')); /* Establecer el localismo */
      // setlocale(LC_MONETARY, \infosite('site_format_currency')); /* Establecer el localismo */
      if(\infosite($key) == true && $_SERVER["HTTPS"] != "on") { header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); exit(); }
    } catch (\Exception $e) {
      exit($e->getMessage());
    }
  }

  public static function pacmec_parse_value($option_value)
  {
    switch ($option_value) {
      case 'true':
        return true;
        break;
      case 'false':
        return false;
        break;
      case 'null':
        return null;
        break;
      default:
        return $option_value;
        break;
    }
  }

  public static function pacmec_init_route()
  {
    try {
      global $PACMEC;
      $PACMEC['route'] = new \PACMEC\System\Route();

      switch ($PACMEC['path']) {
        case '/pacmec-api':
        case '/pacmec-api-doc':
          throw new \Exception("No implementado para esta version.", 1);
          break;
        case '/pacmec-update':
          echo "Actualizando:\n";
          echo ".";
          $result_pacmec = exec("git pull origin main");
          echo ".\n";
          echo "Resultado: " . json_encode($result_pacmec);
          exit;
          break;
        case '/robots.txt':
          $filerobots = dirname(PACMEC_PATH) . '/robots.txt';
          if(!is_file($filerobots) || !file_exists($filerobots)) {
            header('Content-Type: text/plain');
            echo "# PACMEC autogenerated robots.txt\n";
            echo "User-agent: *\n";
            echo "Allow: *\n";
            echo "Crawl-delay: 10\n";
            //echo "Sitemap: ".infosite('siteurl')."/pacmec-sitemap.xml\n";
            exit;
          }
          break;
        default:
          $PACMEC['route']->getBy('request_uri', $PACMEC['path']);
          if($PACMEC['route']->id <= -1){
            $s_path = __url_s_($PACMEC['path']);
            $PACMEC['route']->getBy('request_uri', $s_path);
          }
          \do_action('route_extends_path');
          if ($PACMEC['route']->permission_access !== null) {
            $check = \validate_permission($PACMEC['route']->permission_access);
            if($check == false){

              if(isUser()) {
                $PACMEC['route']->content = "[pacmec-errors title=\"route_no_access_title\" content=\"route_no_access_content\"][/pacmec-errors]";
              }
              else {
                $PACMEC['route']->layout = 'pages-signin';
                $PACMEC['route']->content = ('[pacmec-form-signin redirect="'.infosite('siteurl').$PACMEC['path'].'"][/pacmec-form-signin]');
              }
            }
          }
      }

      $model_route = $PACMEC['route'];
      if($model_route->id>0&&$model_route->is_actived==true){
        //
        pacmec_add_meta_tag('title', ($GLOBALS['PACMEC']['route']->title));
        pacmec_add_meta_tag('description', ($GLOBALS['PACMEC']['route']->description));
        pacmec_add_meta_tag('keywords', ($GLOBALS['PACMEC']['route']->title).",".$GLOBALS['PACMEC']['route']->keywords);
      } else {
        pacmec_add_meta_tag('title', infosite('sitename'));
        pacmec_add_meta_tag('description', infosite('sitedescr'));
        pacmec_add_meta_tag('keywords', infosite('sitekeywords'));
      }
      pacmec_add_meta_tag('site_name', infosite('sitename'));
      pacmec_add_meta_tag('og:email', strip_tags(infosite('business_email')));
      pacmec_add_meta_tag('og:phone_number', strip_tags(infosite('business_phone_number')));
      pacmec_add_meta_tag('language', $GLOBALS['PACMEC']['lang']);
      pacmec_add_meta_tag('url', infosite('siteurl').$GLOBALS['PACMEC']['path']);

      pacmec_add_meta_tag('favicon', infosite('sitefavicon'));

      if(pacmec_exist_meta('canonical')==false) pacmec_add_meta_tag('canonical', infosite('siteurl').$GLOBALS['PACMEC']['path']);
      if(pacmec_exist_meta('og:image')==false) pacmec_add_meta_tag('image', infosite('sitelogo'));
      if(pacmec_exist_meta('robots')==false && infosite('robots')!=="NaN") { pacmec_add_meta_tag('robots', infosite('siterobots')); } else { pacmec_add_meta_tag('robots', 'index,follow'); };
      if(pacmec_exist_meta('Classification') == false && infosite('Classification')!=="NaN") { pacmec_add_meta_tag('Classification', infosite('Classification')); } else { pacmec_add_meta_tag('Classification', 'Internet'); };
      if(pacmec_exist_meta('author')==false && infosite('author')!=="NaN") { pacmec_add_meta_tag('author', infosite('author')); } else { pacmec_add_meta_tag('author', DevBy()); };
      if(pacmec_exist_meta('og:type')==false) pacmec_add_meta_tag('og:type', 'Website');

      pacmec_add_meta_tag('generator', 'PACMEC 1.0.1');
    } catch (\Exception $e) {
      exit("{$e->getMessage()}\n");
    }

    /*
       else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-api-docs'){
        require_once 'api-doc.php';
        exit;
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-close-session'){
        $redirect = infosite("siteurl").infosite("homeurl");
        $GLOBALS['PACMEC']['session']->close();
        header("Location: ".$redirect);
        echo "<meta http-equiv=\"refresh\" content=\"0; url={$redirect}\">";
        exit;
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-form-sign'){
        $model_route = new PACMEC\ROUTE();
        $model_route->theme = 'system';
        $model_route->component = 'pages-signin';
        $model_route->title = _autoT('signin');
        $args = dataFull();
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-recover-password'){
        $model_route = new PACMEC\ROUTE();
        $model_route->theme = 'system';
        $model_route->component = 'pages-recover-password';
        $model_route->title = _autoT('recover_password');
        $args = dataFull();
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-form-contact'){
        header('Content-Type: application/json');
        $merge = dataFull();
        $args = array_merge(['args'=>$merge], $merge);
        get_part('components/contact-form-backend', PACMEC_CRM_COMPONENTS_PATH, $args);
        exit;
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-staff'){
        $model_route = new PACMEC\ROUTE();
        $model_route->theme = 'system';
        $model_route->component = 'pages-staff';
        $model_route->title = _autoT('pagestaff');
        //$args = dataFull();
      } else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-form-contact-services'){
        header('Content-Type: application/json');
        $merge = dataFull();
        $args = array_merge(['args'=>$merge], $merge);
        get_part('components/services-contact-form-backend', PACMEC_PATH.'/system/', $args);
        exit;
      } else if(isset($detectAPI[1])) {
        /*switch ($detectAPI[1]) {
          case _autoT('%autot_services%'):
            $detectAPI[1] = '%autot_services%';
            break;
          default:
            break;
        }* /
      }
    	$GLOBALS['PACMEC']['req_url'] = $reqUrl;
      if(!isset($model_route))
      {
        $model_route = new PACMEC\ROUTE([
          'page_slug'=>$reqUrl
        ]);
      }
      // Comprobar que exista y que esté activo.
      $GLOBALS['PACMEC']['route'] = $model_route;

    */
    #return $m;
  }

  public static function get_detect_lang()
  {
    global $PACMEC;
    $result = PACMEC_LANG_DEF;
    if (isset($_COOKIE['language']) && !empty($_COOKIE['language']) && isset($PACMEC['glossary'][$_COOKIE['language']])){
      $result = $_COOKIE['language'];
    }
    if(!isset($_COOKIE['language']) || $result !== $_COOKIE['language']) setcookie('language', $PACMEC['lang']);
    return $result;
  }

  public static function get_langs_http() : Array
  {
    global $PACMEC;
    $a = [];
    $tbl_glossary     = $PACMEC['DB']->getTableName('glossary');
    $tbl_glossary_txt = $PACMEC['DB']->getTableName('glossary_txt');
    $glossary_active = false;

  	foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM {$tbl_glossary} ", []) as $option){
      if($option->tag == $PACMEC['lang']) $glossary_active = $option;
  		$a[$option->tag] = ["name" => $option->name, "id"=>$option->id, "dictionary"=>[]];
  	};
    if($glossary_active == false) exit("El lenguaje: $glossary_active no se encuentra en la DB.");
    foreach(\glob(PACMEC_PATH."/i18n/*") as $file_path){
      $file_info = Self::validate_file($file_path);
      if(isset($file_info['translation_for_the_language']) && isset($file_info['version']) && isset($file_info['text_domain'])){
        $slug = $file_info['translation_for_the_language'];
        $text_domain = $file_info['text_domain'];
        if(isset($a[$slug])){
          $info_lang = Self::extract_info_lang($file_path);
          foreach ($info_lang as $key => $value) {
            $a[$slug]['dictionary'][$key] = $value;
          }
          $PACMEC['autoload']['dictionary'][$file_path] = $file_info;
        }
      }
    }
    foreach($PACMEC['DB']->FetchAllObject("SELECT * FROM {$tbl_glossary_txt} WHERE `glossary_id` IN (?) ", [$glossary_active->id]) as $option){
  		$a[$PACMEC['lang']]['dictionary'][$option->slug] = $option->text;
  	};
    return $a;
  }

  public static function extract_info_lang($file_path)
  {
    $info_r = [];
    if((!\is_file($file_path) && !\file_exists($file_path)) || \is_dir($file_path) && \is_file($file_path)) return $info_r;
    $texto = @\file_get_contents($file_path);
    $input_line = \nl2br($texto);
    \preg_match_all('/[*\s](.+)[=]+[\s]+([a-zA-Z0-9]+[^<]+)/mi', $input_line, $detect_array);
    foreach($detect_array[1] as $i=>$lab){ $info_r[\str_replace([], [], ($lab))] = $detect_array[2][$i]; }
    return $info_r;
  }

  public static function validate_file($file_path)
  {
    $info_r = [];
    if((!\is_file($file_path) && !\file_exists($file_path)) || \is_dir($file_path) && \is_file($file_path)) return $info_r;
    $texto = @\file_get_contents($file_path);
    $input_line = \nl2br($texto);
    \preg_match_all('/[*\s]+([a-zA-Z\s\i]+)[:]+[\s]+([a-zA-Z0-9]+[^<]+)/mi', $input_line, $detect_array);
    if(isset($detect_array[1]) && isset($detect_array[2])){
      foreach($detect_array[1] as $i=>$lab){
        $_tag = \str_replace(['  ', ' ', '+'], '_', \strtolower($lab));
        $_val = $detect_array[2][$i];
        $info_r[$_tag] = $_val;
        # echo "{$i}: ";
        # echo "{$lab} - ";
        # echo "{$_tag} ||| ";
        # echo "{$_val}\n";
      }
    }
    return $info_r;
  }

  public static function validate_type_file($file_path)
  {
    if(\is_dir($file_path) && $is_file($file_path)) { return "directory"; }
    else {
  		if(\is_file($file_path) && \file_exists($file_path) && !is_dir($file_path)){
  			$texto = @\file_get_contents($file_path);
  			$input_line = \nl2br($texto);
  			preg_match_all('/[*\s]+([a-zA-Z\s\i]+)[:]+[\s]+([a-zA-Z0-9]+[^<]+)/mi', $input_line, $detect_array);
  			// validar si es traduccion
        $detect = [];
  			foreach($detect_array[1] as $i=>$lab){ $detect[str_replace(['  ', ' ', '+'], '_', strtolower($lab))] = $detect_array[2][$i]; }
  			if(isset($detect['translation_for_the_language']) && isset($detect['version']) && isset($detect['text_domain'])){
  				return "glossary";
  			}
  			// validar si es plugin
        $detect = [];
  			foreach($detect_array[1] as $i=>$lab){ $detect[str_replace(['  ', ' ', '+'], '_', strtolower($lab))] = $detect_array[2][$i]; }
  			if(isset($detect['plugin_name']) && isset($detect['version'])){
  				return "plugin";
  			}
  			// validar si es tema
        $detect = [];
  			foreach($detect_array[1] as $i=>$lab){ $detect[str_replace(['  ', ' ', '+'], '_', strtolower($lab))] = $detect_array[2][$i]; }
  			if(isset($detect['theme_name']) && isset($detect['version'])){
  				return "theme";
  			}
  		}
  	}
  	return "undefined";
  }

  public static function get_current_path()
  {
    $query = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
  	$path = (strtok($query, '?'));
  	$request = \str_replace([PACMEC_HOST], '', $path);
  	$array = explode("/", $request);

    return [
      "query" => $query,
      "path" => $path,
      "request" => $request,
      "array" => $array,
    ];
  }

  /**
  * Ejecutar PACMEC
  *
  * @author FelipheGomez <feliphegomez@gmail.com>
  * @return  : \PACMEC\System\Run
  */
  public static function exec()
  {
    # echo "PACMEC\System\Run::exec()\n";
    return new Self;
  }

  /**
   * IP del cliente
   *
   * @author FelipheGomez <feliphegomez@gmail.com>
   */
  public static function get_ip_address()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      if (\strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
        $iplist = \explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($iplist as $ip) { if (Self::validate_ip($ip)) return $ip; }
      } else {
        if (Self::validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && Self::validate_ip($_SERVER['HTTP_X_FORWARDED'])) return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && Self::validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && Self::validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && Self::validate_ip($_SERVER['HTTP_FORWARDED'])) return $_SERVER['HTTP_FORWARDED'];
    return $_SERVER['REMOTE_ADDR'];
  }

  /**
   * Garantiza que una dirección IP sea una IP válida y no se encuentre dentro del rango de una red privada.
   */
  public static function validate_ip($ip) {
    if (\strtolower($ip) === 'unknown') return false;
    $ip = \ip2long($ip);
    if ($ip !== false && $ip !== -1) {
      $ip = \sprintf('%u', $ip);
      if ($ip >= 0 && $ip <= 50331647) return false;
      if ($ip >= 167772160 && $ip <= 184549375) return false;
      if ($ip >= 2130706432 && $ip <= 2147483647) return false;
      if ($ip >= 2851995648 && $ip <= 2852061183) return false;
      if ($ip >= 2886729728 && $ip <= 2887778303) return false;
      if ($ip >= 3221225984 && $ip <= 3221226239) return false;
      if ($ip >= 3232235520 && $ip <= 3232301055) return false;
      if ($ip >= 4294967040) return false;
    }
    return true;
  }

  /**
  * Sanear datos JSON e INPUTs
  *
  * @author FelipheGomez <feliphegomez@gmail.com>
  * @return  : Array
  */
  public static function post_data_json() : Array
  {
    try {
      $r  =  [];
      $rawData = @\file_get_contents("php://input");
      if(@\json_decode($rawData) !== null) {
        foreach (@\json_decode($rawData) as $k => $v) {
          $r[$k] = $v;
        }
      };
      return $r;
    } catch (\Exception $e) {
      return [];
    }
  }

  /**
   * Retornar informacion recibida
   *
   * @author FelipheGomez <feliphegomez@gmail.com>
   * @return Array
   */
  public static function get_data_full() : Array
  {
    try {
      $json = Self::post_data_json();
      $a = @\array_merge($_GET, $_POST, $json);
      return \is_array($a) ? $a : [];
    } catch (\Exception $e) {
      return [];
    }
  }
}
