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

class Comments extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'comments';
  const COLUMNS_AUTO_T        = [];
	public $rating_number = 0;
	public $rating_porcen = 0;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->comment_id)) $this->get_by('id', $opts->comment_id);
  }

  public function __toString() : string
  {
    return "{$this->comment}";
  }

  public function create()
  {
    //if(empty($this->host)) $this->host = $GLOBALS['PACMEC']['host'];
    if(empty($this->host)) $this->host = '*';
  	$columns = $this->getColumns();
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
    		if(
          isset($this->{$i})
          && $i!=='id'
          && $i!=='created'
          && $i!=='approved'
          && in_array($i, [
            'uri'
            , 'display_name'
            , 'user_id'
            , 'email'
            , 'comment'
            , 'vote'
            , 'host'
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
        $this->get_by_id($insert->id);
      	return $this->id;
      }
      return 0;
  	}catch (Exception $e){
  		return 0;
  	}
  }

  public static function get_all_by($k, $v)
  {
    try {
      $r = [];
      $sql = "Select * from `".Self::get_table()."` WHERE `{$k}`=? AND `host` IN ('*', ?)";
			$result = Self::link()->FetchAllObject($sql, [$v, $GLOBALS['PACMEC']['host']]);
      if($result !== false){ foreach ($result as $item) { $r[] = new Self((object) ['comment_id'=>$item->id]); } }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public function set_all($obj)
  {
    global $PACMEC;
    Parent::set_all($obj);
    if($this->isValid()){
      if($this->vote>0){
        $this->rating_number = (int) ($this->vote);
        $this->rating_porcen = (int) (($this->vote*100)/5);
      }
    }
    ###echo json_encode($this, JSON_PRETTY_PRINT);
    ###exit;
  }
}
