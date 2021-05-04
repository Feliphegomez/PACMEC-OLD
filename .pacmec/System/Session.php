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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

namespace PACMEC\System;

class Session
{
 	#public  $isGuest          = true;
 	public  $user               = null;
 	public  $permission_group   = null;
 	public  $permissions_items  = [];
 	public  $permissions        = [];
 	public  $notifications      = [];
 	public  $shopping_cart      = [];
 	public  $emails_boxes       = [];
  public  $subtotal_cart      = 0;
  public  $remote_ip          = "";

 	/**
 	* Inicializa la sesión
 	*/
 	public function __construct()
 	{
    global $PACMEC;
    $this->refreshSession();
    if(isset($PACMEC['fullData']['pacmec_close'])&&!empty($PACMEC['fullData']['pacmec_close'])) {
      $this->close();
      $url = infosite('siteurl')."/{$PACMEC['permanents_links']['%pacmec_signin%']}";
      header("Location: {$url}");
      exit;
    };
    if (isset($PACMEC['fullData']['update-cart'])){
      foreach ($PACMEC['fullData'] as $key => $value) {
        if($key !== 'update-cart'){
          $explode = explode(':', $key);
          if(isset($explode[1])){
            $this->update_quantity_in_cart($explode[1], $value, $explode[0]);
          }
        }
      }
    } elseif (isset($PACMEC['fullData']['discard-in-cart'])){
      $this->remove_from_cart($PACMEC['fullData']['discard-in-cart']);
    }
    elseif (
      isset($GLOBALS['PACMEC']['fullData']['add-product-in-cart'])
      && isset($GLOBALS['PACMEC']['fullData']['type'])
      && isset($GLOBALS['PACMEC']['fullData']['quantity'])
    ){
      switch ($GLOBALS['PACMEC']['fullData']['type']) {
        case 'product':
           if(isset($GLOBALS['PACMEC']['fullData']['product_id'])){
             $result = $this->add_in_cart($GLOBALS['PACMEC']['fullData']['product_id'], $GLOBALS['PACMEC']['fullData']['quantity']);
             $url_cart = __url_s('/%cart_slug%');
             //$msg_add_product = \PHPStrap\Util\Html::tag('div', __a($result), ['alert alert-success']);
             //echo "<meta http-equiv=\"refresh\" content=\"0;\" />";
             //header("Refresh:0");
           }
          break;
        default:
          break;
      }
    }
 	}

 	public function add_alert(string $message, string $title=null, string $url=null, int $time=null, string $uniqid=null, string $icon=null)
 	{
 		$time = $time==null ? time() : $time;
 		$uniqid = $uniqid==null ? uniqid() : $uniqid;
 		$icon = $icon==null ? "fas fa-bell" : $icon;
 		$url = $url==null ? "#" : $url;
 		$title = $title==null ? "Nueva notificacion" : $title;
 		$date = date('Y-m-d H:i:s', $time);
 		$alert = [
 			"title"=>$title,
 			"message"=>$message,
 			"time"=>$time,
 			"uniqid"=>$uniqid,
 			"date"=>$date,
 			"url"=>$url,
 			"icon"=>$icon,
 		];
 		if(!isset($this->notifications[$uniqid])){
 			$this->set($uniqid, $alert, 'notifications');
 			// $this->notifications[$uniqid] = $_SESSION['notifications'][$uniqid] = $alert;
 		};
 	}

 	public function add_permission(string $tag, $obj=null):bool
 	{
 		$tag = strtolower($tag);
 		if($obj !== null){
 			$obj = (object) $obj;
 		} else {
 			$obj = (object) [
 				"id"=>999999999999999999999999,
 				"tag"=>$tag,
 				"name"=>$tag,
 				"description"=>$tag,
 			];
 		}
 		if(!isset($this->permissions_items[$tag])){
 			$this->permissions_items[$tag] = $_SESSION['permissions_items'][$tag] = $obj;
 		}
 		if(isset($_SESSION['permissions'])&&!in_array($tag, $_SESSION['permissions'])) $this->permissions[] = $_SESSION['permissions'][] = $tag;
 		return true;
 	}

 	public function set($k, $v, $l=null)
 	{
 		if($l == null){
 			$this->{$k} = $_SESSION[$k] = $v;
 		} else {
 			if(is_array($this->{$l})){
 				$this->{$l}[$k] = $_SESSION[$l][$k] = $v;
 			} else {
 				$this->{$l}->{$k} = $_SESSION[$l][$k] = $v;
 			}
 		}
 	}

 	public function refreshSession()
 	{
    try {
   		$this->user             = new \stdClass();
   		$this->permission_group = new \stdClass();
   		$this->user = (Object) [];
     	$this->permissions_items  = [];
     	$this->permissions        = [];
     	$this->notifications      = [];
     	$this->shopping_cart      = [];
      $this->subtotal_cart      = 0;
      $this->remote_ip = \getIpRemote();

      if(\isUser()) $this->getById(\userID());

      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}` WHERE `session_id` IN (?)", [session_id()]) as $pedid) {
        switch ($pedid->type) {
          case 'product':
            $data = new \PACMEC\System\Product((object) ['id'=>$pedid->ref_id]);
            $this->subtotal_cart += ($data->price*$pedid->quantity);
            break;
          default:
            $data = null;
            break;
        }
        $pedid->data = $data;
        $this->shopping_cart["{$pedid->type}:{$pedid->ref_id}"] = $pedid;
      }
    }
    catch(Exception $e){
      echo $e->getMessage();
      exit();
    }
 	}

  public function getById($user_id=null)
  {
    $user_id = $user_id!==null ? $user_id : \userID();
    //$tbl = $GLOBALS['PACMEC']['DB']->getTableName('users');
    //$dataUser = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$tbl}` WHERE `id`=? ", [ $user_id ]);
    $this->setAll(new \PACMEC\System\Users((object) ['user_id'=>$user_id]));
    return $this;
  }

 	public function setAll($user)
 	{
 		foreach($user as $a => $b){ $this->user->{$a} = $b; }
    if(isset($this->user->permissions) && $this->user->permissions !== null && $this->user->permissions > 0 && count($this->permissions)==0){
      $result = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT E.*
        FROM `{$GLOBALS['PACMEC']['DB']->getTableName('permissions')}` D
        JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('permissions_items')}` E
        ON E.`id` = D.`permission`
        WHERE D.`group` IN (?)", [$this->user->permissions]);
      if($result !== false && count($result) > 0){
        foreach($result as $perm){
          $this->add_permission($perm->tag, $perm);
        }
      }
      $result = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('permissions_group')}` WHERE `id` IN (?)", [$this->user->permissions]);
      if($result !== false){
        $this->permission_group = $result;
      }
    }
    $result = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT E.*
 			FROM `{$GLOBALS['PACMEC']['DB']->getTableName('permissions_users')}` D
 			JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('permissions_items')}` E
 			ON E.`id` = D.`permission`
 			WHERE D.`user_id` IN (?)", [$this->user->id]);
 		if($result !== false && count($result) > 0){
 			foreach($result as $perm){
 				$this->add_permission($perm->tag, $perm);
 			}
 		}
    $this->permissions = array_keys($this->permissions_items);
    foreach (\PACMEC\System\Notifications::get_all_by_user_id(\userID(), false) as $item) {
      $this->notifications[] = $this->add_alert($item->data->message, $item->data->title, $item->host, strtotime($item->created), $item->id);
    }
    foreach ($this as $k => $v) {
      $_SESSION[$k] = is_object($v) ? (Array) $v : $v;
    }
 	}

 	/**
 	* Retorna todos los valores del array de sesión
 	* @return el array de sesión completo
 	*/
 	public function getAll()
 	{
 		#$this->refreshSession();
 		return isset($_SESSION['user']) ? $this : [];
 	}

 	/**
 	* Cierra la sesión eliminando los valores
 	*/
 	public static function close()
 	{
 		\session_unset();
 		\session_destroy();
 	}

 	/**
 	* Retorna el estatus de la sesión
 	* @return string el estatus de la sesión
 	*/
 	public static function getStatus()
 	{
 		switch(\session_status())
 		{
 			case 0:
 				return "DISABLED";
 				break;
 			case 1:
 				return "NONE";
 				break;
 			case 2:
 				return "ACTIVE";
 				break;
 		}
 	}

 	/**
 	* Retorna string default
 	* @return string
 	*/
 	public function __toString()
 	{
    // COLOCAL LABEL O GUEST
 		return json_encode($this->getAll());
 	}

 	/**
 	* Retorna array default
 	* @return string
 	*/
 	public function __sleep()
 	{
 		return array_keys($this->getAll());
 	}

 	public function getId()
 	{
 		return !isset($_SESSION['user']['id']) ? 0 : $_SESSION['user']['id'];
 	}

 	public function login($args = [])
 	{
 		$args = (object) $args;
 		if(isset($args->nick) && isset($args->hash)){
 			$result = $this->validateUserDB($args->nick);
 			switch($result){
 				case "error":
 				case "no_exist":
 				case "inactive":
 					return $result;
 					break;
 				case $result->id > 0:
 					if (\password_verify($args->hash, $result->hash) == true) {
 						if (!\headers_sent()) {
 			          \session_regenerate_id(true);
 			      }
 						$this->setAll($result);
 						return "success";
 					} else {
 						return "invalid_credentials";
 					}
 					break;
 				default:
 					return "error";
 					break;
 			}
 		}
 	}

 	public function validateUserDB($nick_or_email='')
 	{
 		try {
 			$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `username`=? AND `status` IN (1) ";
 			$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `username`=? ";
 			$result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$nick_or_email]);
 			if($result == false){
 				$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `email`=? AND `status` IN (1) ";
 				$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `email`=? ";
 				$result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$nick_or_email]);
 			}
 			if($result !== false && isset($result->id)){
 				if($result->status == 0){
 					return "inactive";
 				}
 				return $result;
 			}
 			return "no_exist";
 		}
 		catch(Exception $e){
 			#echo $e->getMessage();
 			return "error";
 		}
 	}

 	public function validateUserDB_recover($key,$email)
 	{
 		try {
 			$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `keyrecov` IN (?) AND `email` IN (?) ";
 			$result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$key,$email]);
 			if($result !== false && isset($result->id)){
 				if($result->status == 0){
 					return "inactive";
 				}
 				return $result;
 			}
 			return "no_exist";
 		}
 		catch(Exception $e){
 			#echo $e->getMessage();
 			return "error";
 		}
 	}

  public static function getUserId()
  {
    return \userID();
  }

 	public function save()
 	{
 		try {
 			$user_id = Self::getUserId();
      /*
      * https://www.php.net/manual/es/filter.filters.sanitize.php
        "username" => '([^A-Za-z0-9])',
        "email" => '([^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$])',
        "names" => '([^[\\p{L}\\. \'-]+$])',
        "surname" => '([^[\\p{L}\\. \'-]+$])',
        "identification_type" => '([^0-9])',
        "identification_number" => '([^0-9])',
        "phone" => '([^0-9])',
        "mobile" => '([^0-9])',
      */
      $clmns = [
        "names",
        "surname",
        "identification_type",
        "identification_number",
        "phone",
        "mobile",
      ];
      $save_data = [];
      $ib = (array) $this->user;
      foreach ($clmns as $i => $key) {
        if(in_array($key, array_keys($ib))){
          switch ($key) {
            case 'phone':
            case 'mobile':
              $save_data[$key] = str_replace([' ', '-', '.', '(', ')'], [''], $this->user->{$key});
              break;
            case 'identification_number':
              $save_data[$key] = str_replace([' ', '.', '(', ')'], [''], $this->user->{$key});
              break;
            default:
              $save_data[$key] = $this->user->{$key};
              break;
          }
        }
      }
      $labels = "`";
      $labels .= implode("`=?,`", array_keys($save_data));
      $labels .= "`=?";
      $result = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE  `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` SET {$labels} WHERE `id`={$user_id}", array_values($save_data));
 			if($result==true) {
 				foreach ($save_data as $key => $value) {
 					$_SESSION['user'][$key] = $value;
 				}
 			};
 			return $result;
 		}
 		catch(Exception $e){
 			#echo $e->getMessage();
 			return false;
 		}
 	}

 	public function register()
 	{
 		try {


      /*
      $clmns = [
        "names",
        "surname",
        "identification_type",
        "identification_number",
        "phone",
        "mobile",
      ];
      $save_data = [];
      $ib = (array) $this->user;
      foreach ($clmns as $i => $key) {
        if(in_array($key, array_keys($ib))){
          switch ($key) {
            case 'phone':
            case 'mobile':
              $save_data[$key] = str_replace([' ', '-', '.', '(', ')'], [''], $this->user->{$key});
              break;
            case 'identification_number':
              $save_data[$key] = str_replace([' ', '.', '(', ')'], [''], $this->user->{$key});
              break;
            default:
              $save_data[$key] = $this->user->{$key};
              break;
          }
        }
      }
      $labels = "`";
      $labels .= implode("`=?,`", array_keys($save_data));
      $labels .= "`=?";
      $result = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE  `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` SET {$labels} WHERE `id`={$user_id}", array_values($save_data));
 			if($result==true) {
 				foreach ($save_data as $key => $value) {
 					$_SESSION['user'][$key] = $value;
 				}
 			};
 			return $result;
      */
 		}
 		catch(Exception $e){
 			#echo $e->getMessage();
 			return false;
 		}
 	}

  public function save_info_access()
  {
    try {
      $user_id = Self::getUserId();
      $clmns = [
        "username",
        "email",
      ];
      $save_data = [];
      $ib = (array) $this->user;
      foreach ($clmns as $i => $key) { if(in_array($key, array_keys($ib))){ $save_data[$key] = $this->user->{$key}; } }
      $labels = "`";
      $labels .= implode("`=?,`", array_keys($save_data));
      $labels .= "`=?";
      $result = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` SET {$labels} WHERE `id`={$user_id}", array_values($save_data));
      if($result==true) {
        foreach ($save_data as $key => $value) {
          $_SESSION['user'][$key] = $value;
        }
      };
      return $result;
    }
    catch(Exception $e){
      #echo $e->getMessage();
      return false;
    }
  }

 	public function recover_pass($user_id)
 	{
 		try {
 			$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `id`=? ";
 			$user = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$user_id]);
 			if($user == false) return $user;
 			$key = \randString(32);
 			$urlrecover = \siteinfo('siteurl').__url_s("/%forgotten_password_slug%")."?kr={$key}&ue=".urlencode($user->email);
 			$updated = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` SET `keyrecov`=? WHERE `id`={$user_id}", [$key]);
 			if($updated !== false){
        $mail = new \PACMEC\System\EmailsTemplates((object) ['template_slug'=>infosite('register_forgotten_password')]);
        if($mail->isValid()){
          $mail->set_autot([
   			    '%sitelogo%',
   			    '%sitename%',
   			    '%PreviewText%',
   			    '%recover_password_from%',
   			    '%recover_password_text%',
   			    '%display_name%',
   			    '%username%',
   			    '%email%',
   			    '%urlrecover%',
   			    '%siteurl%',
   			    '%recover_password%',
          ], [
   			    infosite('sitelogo'),
   			    infosite('sitename'),
   			    infosite('sitedescr'),
   			    __a('recover_password_from'),
   			    __a('recover_password_text'),
   			    "{$user->names} {$user->surname}",
   			    "{$user->username}",
   			    $user->email,
   			    $urlrecover,
   			    infosite('siteurl').infosite('homeurl'),
   			    __a('recover_password'),
          ]);
          return $result_send = $mail->send(__a('recover_password'), $user->email, "{$user->names} {$user->surname}");
        }

        /*
 			  $email_contact_received = $user->email;
 			  $e_subject = _autoT('recover_password_from');
 			  $template_org = file_get_contents(PACMEC_PATH.'templates-mails/recover-password.php', true);
 				$tags_in = [
 			  ];
 			  $tags_out = [
 			  ];
 			  $template = \str_replace($tags_in, $tags_out, $template_org);
 			  $mail = new PHPMailer(true);
 			  try {
 			      //Server settings
 			      //$mail->SMTPDebug = 2;                 // Enable verbose debug output
 			      $mail->isSMTP();                      // Set mailer to use SMTP
 			      $mail->Host       = SMTP_HOST;        // Specify main and backup SMTP servers
 			      $mail->SMTPAuth   = SMTP_AUTH;        // Enable SMTP authentication
 			      $mail->Username   = SMTP_USER;        // SMTP username
 			      $mail->Password   = SMTP_PASS;        // SMTP password
 			      $mail->SMTPSecure = SMTP_SECURE;      // Enable TLS encryption, `ssl` also accepted
 			      $mail->Port       = SMTP_PORT;        // TCP port to connect to
 			      $mail->CharSet    = infosite('charset');

 			      //Recipients
 			      $mail->setFrom($email_contact_from, infosite('sitename'));
 			      $mail->addAddress($email_contact_received);     // Add a recipient Name is optional (, 'name')
 			      // $mail->addReplyTo($email_contact_from, $e_subject);

 			      if(SMTP_CC!==false) $mail->addCC(SMTP_CC);
 			      if(SMTP_BCC!==false) $mail->addBCC(SMTP_BCC);

 						// Content
 			      $mail->isHTML(true);                                  // Set email format to HTML
 			      $mail->Subject = $e_subject;
 			      $mail->Body    = ($template);
 			      $mail->AltBody = \strip_tags($template);
 			      return ($mail->send());
 			  } catch (Exception $e) {
 			      return false;
 			  }
        */
 			}
 		} catch (\Exception $e) {
 			return false;
 		}
 	}

 	public function change_pass($password)
 	{
 		try {
 			$sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` WHERE `id`=? ";
 			$user = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->user->id]);
 			if($user == false) return $user;
 			$hash = password_hash($password, PASSWORD_DEFAULT);
 			$updated = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName('users')}` SET `hash`=?,`keyrecov`=? WHERE `id`={$this->user->id}", [$hash,NULL]);
 			return $updated;
 		} catch (\Exception $e) {
 			return false;
 		}
	}

  public function check_password($password)
  {
    return \password_verify($password, $GLOBALS['PACMEC']['session']->user->hash);
  }

  public function remove_from_cart($id, $session_id=null)
  {
    try {
      $session_id = !isset($session_id) ? session_id() : $session_id;
      $sql = "DELETE FROM `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}` WHERE `session_id`=? AND `id`=? ";
      $result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$session_id, $id]);
      if($result==true){
        $this->refreshSession();
      }
    } catch (\Exception $e) {
      return "add_to_cart_fail";
    }
  }

  public function add_in_cart($item, $quantity, $type='product')
  {
    try {
      switch ($type) {
        case 'product':
          // $product = null;
          if(is_numeric($item)){
            $search_sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}` WHERE `session_id`=? AND `type`=? AND `ref_id`=?";
            $search = $GLOBALS['PACMEC']['DB']->FetchObject($search_sql, [
              session_id(),
              $type,
              $item
            ]);
            if($search !== false) {
                $this->update_quantity_in_cart($item, ($quantity+$search->quantity), $type);
            } else {
              $product = new \PACMEC\System\Product((object) ['id'=>$item]);
              if($product->isValid()){
                $a_c  = (int) $product->available;
                if($a_c>0){
                  $a_cc = !isset($this->shopping_cart["product:{$product->id}"]) ? 0 : $this->shopping_cart["product:{$product->id}"]->quantity;
                  $a_i = ($a_c >= ($a_cc+$quantity)) ? ($a_cc+$quantity) : $a_c;
                  $id_shop = !isset($this->shopping_cart["product:{$product->id}"]) ? null : $this->shopping_cart["product:{$product->id}"]->id;
                  $sql = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}` (`session_id`, `type`, `ref_id`, `quantity`)
                    SELECT ?, ?, ?, ?
                    WHERE NOT EXISTS(SELECT 1 FROM `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}` WHERE `session_id`=? AND `type`=? AND `ref_id`=?)";

                  $result = $GLOBALS['PACMEC']['DB']->FetchObject($sql,
                    [
                      session_id(),
                      $type,
                      $item,
                      $a_i,

                      session_id(),
                      $type,
                      $item,
                    ]
                  );
                  $this->refreshSession();
                  //header("Location: ".$_SERVER['PHP_SELF']);
                  return $result == true ? "add_to_cart_success" : "add_to_cart_fail";
                } else {
                  return "product_not_available";
                }
              }
            }
          }
          return "add_to_cart_fail";
          break;
        default:
          break;
      }
    } catch (\Exception $e) {
      return "add_to_cart_fail";
    }
  }

  public function update_quantity_in_cart($item, $quantity, $type='product')
  {
    try {
      switch ($type) {
        case 'product':
          // $product = null;
          if(is_numeric($item)){
            $product = new \PACMEC\System\Product((object) ['id'=>$item]);
            if($product->isValid()){
              $a_c  = (int) $product->available;
              if($a_c>0){
                $a_cc = !isset($this->shopping_cart["product:{$product->id}"]) ? 0 : $this->shopping_cart["product:{$product->id}"]->quantity;
                $a_i = ($a_c >= ($quantity)) ? ($quantity) : $a_c;
                $id_shop = !isset($this->shopping_cart["product:{$product->id}"]) ? null : $this->shopping_cart["product:{$product->id}"]->id;
                $result = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName('shoppings_carts')}`
                SET `session_id`=?, `type`=?, `ref_id`=?, `quantity`=? WHERE `id`=?",
                [
                  session_id(),
                  $type,
                  $item,
                  $a_i,
                  $id_shop,
                ]);
                $this->refreshSession();
                //unset($_POST);
                //header("Location: ".$_SERVER['PHP_SELF']);

                return $result == true ? "update_to_cart_success" : "update_to_cart_fail";
              } else {
                return "product_not_available";
              }
            }
          }
          return "update_to_cart_fail";
          break;
        default:
          break;
      }
    } catch (\Exception $e) {
      return "update_to_cart_fail";
    }
  }

  public function get_cart_table_html($items=null)
  {
    $items = ($items == null) ? $this->shopping_cart : $items;
    //$table = \PHPStrap\Table::borderedTable();
    $thead = \PHPStrap\Util\Html::tag('thead',
      \PHPStrap\Util\Html::tag('tr',
          \PHPStrap\Util\Html::tag('th', '',              ['pro-thumbnail'], [])
        . \PHPStrap\Util\Html::tag('th', __a('detail'),   ['pro-title'], [])
        . \PHPStrap\Util\Html::tag('th', __a('price'),    ['pro-price'], [])
        . \PHPStrap\Util\Html::tag('th', __a('quantity'), ['pro-quantity'], [])
        . \PHPStrap\Util\Html::tag('th', __a('subtotal'), ['pro-subtotal'], [])
        . \PHPStrap\Util\Html::tag('th', '',              ['pro-remove'], ['width'=>'5%'])
      , [], [])
    , [], []);
    /*
    $table->addHeaderRow([
      'Thumb'
      , 'Details'
      , 'Price'
      , 'Quantity'
      , 'Total'
      , 'Remove'
    ]);
    */
    $rows = '';
    foreach ($items as $key=>$item) {
      $url_item = isset($item->data->link_href) ? $item->data->link_href : "#";
      $thumb_uri = (isset($item->data->thumb) ? $item->data->thumb : infosite('default_picture'));
      $img = \PHPStrap\Util\Html::tag('img', '', ['img-fluid'], ['src'=>$thumb_uri], true);
      $rows .= \PHPStrap\Util\Html::tag('tr',
          \PHPStrap\Util\Html::tag('td', \PHPStrap\Util\Html::tag('a', $img, [], ['href'=>$url_item]), ['pro-thumbnail'], [])
        . \PHPStrap\Util\Html::tag('td', \PHPStrap\Util\Html::tag('a', $item->data->name, [], ['href'=>$url_item]), ['pro-title'], [])
        . \PHPStrap\Util\Html::tag('td', formatMoney($item->data->price), ['pro-price'], [])
        // . \PHPStrap\Util\Html::tag('td', "{$item->quantity} {$item->data->unid}", ['pro-quantity'], [])
        . \PHPStrap\Util\Html::tag('td',
            \PHPStrap\Util\Html::tag('div',
              \PHPStrap\Util\Html::tag('div',
                \PHPStrap\Util\Html::tag('input', '', ['cart-plus-minus-box'], ["name"=> $key, "value"=>$item->quantity, "type"=>"text", "max"=>((int) $item->data->available), "step"=>"1"], true)
              , ['cart-plus-minus'], [])
            , ['quantity'], [])
          , ['pro-quantity'], [])
        . \PHPStrap\Util\Html::tag('td', \PHPStrap\Util\Html::tag('span', formatMoney($item->data->price*$item->quantity), [], []), ['pro-subtotal'], [])
        . \PHPStrap\Util\Html::tag('td', \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['pe-7s-trash'], []), [], ['href'=>__url_s('/%cart_slug%?discard-in-cart='.$item->id)]), ['pro-remove'], [])
      , [], []);


      /*
      $table->addRow([
        \PHPStrap\Util\Html::tag('a', $img, [], ["href"=>$url_item])
      ]);*/
    }
    $tbody = \PHPStrap\Util\Html::tag('tbody', $rows, [], []);
    return \PHPStrap\Util\Html::tag('table', $thead.$tbody, ['table', 'table-bordered'], []);
  }

}
