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

class Users extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME                = 'users';
  public $username                = 'guest';

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(isset($opts->user_id)) $this->get_by_id($opts->user_id);
    elseif(isset($opts->user_nick)) $this->get_by('username', $opts->user_nick);
    elseif(isset($opts->user_email)) $this->get_by('email', $opts->user_email);
  }

  public function __toString() : String
  {
    return $this->username;
  }

  public function set_all($obj)
  {
    global $PACMEC;
    $obj = (object) $obj;
    if(isset($obj->identification_number)) $obj->identification_number = str_replace([' ', '.', '(', ')'], [''], $obj->identification_number);
    if(isset($obj->phone)) $obj->phone = str_replace([' ', '-', '.', '(', ')'], [''], $obj->phone);
    if(isset($obj->mobile)) $obj->mobile = str_replace([' ', '-', '.', '(', ')'], [''], $obj->mobile);
    Parent::set_all($obj);
    if($this->isValid()){
    }
  }

  public function create()
  {
  	$columns = $this->getColumns();
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
    		if(
          isset($this->{$i})
          && $i!=='id'
          && in_array($i, [
            'status'
            , 'username'
            , 'email'
            , 'names'
            , 'surname'
            , 'identification_type'
            , 'identification_number'
            , 'phone'
            , 'mobile'
            , 'hash'
            , 'permissions'
          ])
        ){
    			$columns_f[] = $i;
    			$columns_a[] = "?";
    			$columns_b[] = " `{$i}`=? ";
    			$items_send[] = $this->{$i};
    		}
    	}
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
      	return $this->id = $insert->id;
      }
      return 0;
  	}catch (Exception $e){
  		return 0;
  	}
  }
}
