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

class Route extends \PACMEC\System\ModeloBase
{
	public $id = -1;
	public $is_actived = 1;
	public $parent = null;
	public $permission_access = null;
	public $title = 'no_found';
	public $theme = null;
	public $description = 'No Found';
	public $content = '';
	public $request_uri = '/404';
	public $request_host = '/404';
	public $layout = 'pages-error';
	public $keywords = '';
	public $meta = [];
	public $rating_number = 0;
	public $rating_porcen = 0;
	public $comments = [];

	public function __construct($args=[])
	{
		$args = (array) $args;
		parent::__construct("routes", false);
		if(isset($args['id'])){ $this->getPublicBy('id', $args['id']); }
		else if(isset($args['request_uri'])){ $this->getPublicBy('request_uri', $args['request_uri']); }
	}

	public static function encodeURIautoT(string $page_slug) : string
	{
		$url_explode = explode('/', $page_slug);
		if(!isset($url_explode[1]) || empty($url_explode[1])) return $page_slug;
		switch ($url_explode[1]) {
			case ('%autot_services%'):
				$url_explode[1] = _autoT('%autot_services%');
				break;
			default:
				break;
		}
		return implode('/', $url_explode);
	}

	public static function decodeURIautoT(string $page_slug) : string
	{
		$url_explode = explode('/', $page_slug);
		switch ($url_explode[1]) {
			case _autoT('%autot_services%'):
				$url_explode[1] = '%autot_services%';
				break;
			default:
				break;
		}
		return implode('/', $url_explode);
	}

	public static function allLoad()
	{
		$r = [];
		if(!isset($GLOBALS['PACMEC']['DB'])){ return $r; }
		foreach($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM {$this->getTable()}", []) as $menu){
			$r[] = new Self($menu);
		}
		return $r;
	}

	public function getBy($a,$b)
	{
		return $this->getPublicBy($a,$b);
	}

	public function getById($a)
	{
		return $this->getPublicBy('id',$a);
	}

	public function getPublicBy($column='id', $val="")
	{
		try {
			global $PACMEC;
			$this->setAll(Self::FetchObject(
				"SELECT * FROM {$this->getTable()}
					WHERE `{$column}`=?
					AND `host` IN ('*', ?)
					"
				, [
					$val,
					$PACMEC['host']
				]
			));
			return $this;
		}
		catch(\Exception $e){
			return $this;
		}
	}

	function setAll($arg=null)
	{
		global $PACMEC;
		$redirect = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : infosite("siteurl").$GLOBALS['PACMEC']['path'];
		$url_login = infosite("siteurl").__url_s("/%pacmec_signin%");

		if($arg !== null){
			if(\is_object($arg) || \is_array($arg)){
				$arg = (array) $arg;
				switch ($arg['permission_access']) {
					case null:
						break;
					default:
						$check = \validate_permission($arg['permission_access']);
						if($check == false){
							//if(\isGuest()){ $arg['layout'] = 'pages-signin'; } else { $arg['layout'] = 'pages-error'; }
							$arg['layout'] = 'pages-error';
							//$this->layout = 'pages-signin';
							$arg['content'] = "[pacmec-errors title=\"route_no_access_title\" content=\"route_no_access_content\"][/pacmec-errors]";
						}
						break;
				}
				foreach($arg as $k=>$v){
					switch ($k) {
						case 'page_slug':
							$this->{$k} = \__url_s(SELF::encodeURIautoT($v));
							break;
						default:
							$this->{$k} = ($v);
							break;
					}
				}
			}

			if(!$this->isValid()){
				$_explo = explode('/', $GLOBALS['PACMEC']['path']);
				$_exploder = [];
				foreach ($_explo as $key => $value) {
					if(!empty($value)) $_exploder[] = $value;
				}
				if (isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%pacmec_signin%']) {
					$this->id = 1;
					$this->request_uri = $GLOBALS['PACMEC']['path'];
					$this->theme = null;
					$this->layout = 'pages-signin';
					$this->title = __a('signin');
					//$this->setAll($PACMEC['route']);
					/*
					require_once PACMEC_PATH.'/.prv/forms/signin.php';
					exit;
					*/
					if(\isUser()){
						header("Location: ".$redirect);
					}

		      //
		      //echo "<meta http-equiv=\"refresh\" content=\"0; url={$redirect}\">";
				} elseif (isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%forgotten_password_slug%']) {
					$this->id = 1;
					$this->request_uri = $GLOBALS['PACMEC']['path'];
					$this->theme = null;
					$this->layout = 'pages-forgotten-password';
					$this->title = __a('pacmec_forgotten_password');
					//$this->setAll($PACMEC['route']);
					/*
					require_once PACMEC_PATH.'/.prv/forms/signin.php';
					exit;
					*/
					if(\isUser()){
						header("Location: ".$redirect);
					}

		      //
		      //echo "<meta http-equiv=\"refresh\" content=\"0; url={$redirect}\">";
				}
				else if (isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%pacmec_meaccount%']) {
					if(!\isUser()){
						//$redirect = infosite("siteurl").infosite("homeurl");
						header("Location: ".$url_login."?&redirect=".urlencode($redirect));
					}
					$this->id = 1;
					$this->request_uri = $GLOBALS['PACMEC']['path'];
					$this->theme = null;
					$this->layout = 'me-account';
					$this->title = __a('me_account');
					$this->description = __a('me_account_descr');
					$this->user = $GLOBALS['PACMEC']['session'];
		      //echo "<meta http-equiv=\"refresh\" content=\"0; url={$redirect}\">";
				} else {
					$this->layout = 'pages-error';
					$this->content = "[pacmec-errors title=\"route_no_actived_title\" content=\"route_no_actived_content\"][/pacmec-errors]";
				}
			 /*
			 else if(isset($detectAPI[1]) && $detectAPI[1] == 'pacmec-close-session'){
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
			 }
			 */
			}
			//$this->title = __a($this->title);
			// $this->description = __a($this->description);
			//$this->getMeta();

		}
		if(is_null($this->theme)) $this->theme = \infosite('theme_default');
		if(\validate_theme($this->theme)==false) $this->theme = \infosite('theme_default');
		$acti = \activation_theme($this->theme);
		if($this->id <= 0){
			$this->layout = 'pages-error';
			$this->content = "[pacmec-errors title=\"error_404_title\" content=\"error_404_content\"][/pacmec-errors]";
		} else {
			$this->keywords .= ','.infosite('sitekeywords');
		}
		if(empty($this->keywords)) $this->keywords = infosite('sitekeywords');
		$this->load_ratings();
		/*

		  public static function get_all_by($k, $v)
		  {
		    try {
		      $class = get_called_class();
		      $r = [];
		      $sql = "Select * from `".$class::get_table()."` WHERE `{$k}`=?";
					$result = Self::link()->FetchAllObject($sql, [$v]);
		      if($result !== false){ foreach ($result as $item) { $r[] = new $class($item); } }
		      return $r;
		    } catch (\Exception $e) {
		      echo $e->getMessage();
		      return [];
		    }
		  }

		$comments->get_by((object) [
			'uri' => infosite('siteurl') . $PACMEC['path']
		]);*/
		//$this->comments = $comments;
	}

	private function load_ratings()
	{
		global $PACMEC;
		$rating = \PACMEC\System\Ratign::get_all_uri(infosite('siteurl') . $PACMEC['path']);
		$this->rating_number = $rating->rating_number;
		$this->rating_porcen = $rating->rating_porcen;
		$this->comments = $rating->votes;
	}

	public function isValid()
	{
		return $this->id > 0 ? true : false;
	}

  public function getMeta()
  {
    try {
      if($this->id>0){
        $result = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$this->getTable()}_meta` WHERE `route_id`=? ORDER BY `ordering` DESC", [$this->id]);
        if(is_array($result)) {
          $this->meta = [];
          foreach ($result as $meta) {
            $meta->attrs = json_decode($meta->attrs);
            $this->meta[] = $meta;
          }
        }
        return [];
      }
    }
    catch(\Exception $e){
      return [];
    }
  }
}
