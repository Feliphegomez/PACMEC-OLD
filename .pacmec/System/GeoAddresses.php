<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PACMEC\System;

class GeoAddresses extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'geo_addresses';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(isset($opts->address_id)) $this->get_by_id($opts->address_id);
  }

  public function __toString() : string
  {
    return "{$this->mini}";
  }

  public static function remove_from_user($address_id, $user_id=null)
  {
    try {
      global $PACMEC;
      if($user_id == null) $user_id = \userID();
      $sql = "DELETE FROM `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` WHERE `address_id`=? AND `user_id`=?";
      $insert = $PACMEC['DB']->FetchObject($sql, [$address_id, $user_id]);
      if($insert>0){
      	return $insert;
      }
      return 0;
    } catch (\Exception $e) {
      return 0;
    }
  }

  public static function get_all_by_user_id($user_id=null, ...$includes) : Array
  {
    $r = [];
    foreach (Parent::get_all_by_user_id($user_id) as $item) {
      if($item->isValid()){
        $r[] = $item;
      }
    }
    return $r;
  }

  public static function get_all_by_order_id($order_id=null, ...$includes) : Array
  {
    $r = [];
    foreach (Parent::get_all_by_order_id($order_id) as $item) {
      $item->address = new Self((object) ['address_id' => $item->address_id]);
      $r[] = $item;
    }
    return $r;
  }

  public static function table_list_html(array $items) : String
  {
    $table = \PHPStrap\Table::borderedTable();
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-plus']), ['btn btn-sm btn-outline-success btn-hover-success'], [
        'href'=>__url_s("/%pacmec_meaccount%?tab=add_address")
      ])
      , __a('address')
      , ''
    ]);
    foreach ($items as $address) {
      $btns = "";
      $btns .= \PHPStrap\Util\Html::tag('a',
        \PHPStrap\Util\Html::tag('i', '', ['fa fa-trash'])." Eliminar"
      , ['btn btn-sm btn-outline-secondary btn-hover-success'], [
        'href'=>__url_s("/%pacmec_meaccount%?tab=me_addresses&remove_id=$address->id")
      ]);
      $table->addRow([
        // , $address->id
        ''
        , $address->mini
        , $btns
      ]);
    }
    return $table;
  }

  public function create()
  {
  	$columns = $this->getColumns();
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
    		if(isset($this->{$i}) && $i!=='id'){
    			$columns_f[] = $i;
    			$columns_a[] = "?";
    			$columns_b[] = " `{$i}`=? ";
    			$items_send[] = $this->{$i};
    		}
    	}
      $sql = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` (".implode(',', $columns_f).")";
      $sql = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` (".implode(',', $columns_f).")
        SELECT ".implode(",", $columns_a)."
        WHERE NOT EXISTS(SELECT 1 FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE ".implode(" AND ", $columns_b).")";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, array_merge($items_send, $items_send));
      // sleep(1);
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject(
          "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE ".implode(" AND ", $columns_b)
          , $items_send
        );
      if($insert!==false && $insert->id > 0){
        $this->get_by_id($insert->id);
        if(\isUser()&&\infosite('address_in_users')==true){ $this->add_in_user(); }
      	return $insert->id;
      }
      return 0;
  	}catch (Exception $e){
  		return 0;
  	}
  }

  public function add_in_user($user_id=null)
  {
    try {
      if($user_id == null) $user_id = \userID();
      $sql = "INSERT INTO `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` (`address_id`, `user_id`) SELECT ?, ?
      WHERE NOT EXISTS (SELECT 1 FROM `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` WHERE `address_id`=? AND `user_id`=?) ";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->id, $user_id, $this->id, $user_id]);
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject(
          "SELECT `id` FROM `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` WHERE `address_id`=? AND `user_id`=?"
          , [$this->id, $user_id]
        );
      if($insert!==false && $insert->id > 0){
      	return $insert->id;
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
      return 0;
    }
  }

  public function add_in_order($order_id, $type='shipping')
  {
    try {
      $sql = "INSERT INTO `".Self::link()->getTableName("orders_".Self::TABLE_NAME)."` (`address_id`, `order_id`, `type`) SELECT ?, ?, ?
      WHERE NOT EXISTS (SELECT 1 FROM `".Self::link()->getTableName("orders_".Self::TABLE_NAME)."` WHERE `address_id`=? AND `order_id`=? AND `type`=?) ";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->id, $order_id, $type, $this->id, $order_id, $type]);
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject(
          "SELECT `id` FROM `".Self::link()->getTableName("orders_".Self::TABLE_NAME)."` WHERE `address_id`=? AND `order_id`=? AND `type`=?"
          , [$this->id, $order_id, $type]
        );
      if($insert!==false && $insert->id > 0){
      	return $insert->id;
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
      return 0;
    }
  }

  public function set_all($obj)
  {
    global $PACMEC;
    Parent::set_all($obj);
    if($this->isValid()){
    }
  }
}
