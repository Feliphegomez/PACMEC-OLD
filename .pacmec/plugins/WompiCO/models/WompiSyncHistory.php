<?php
/**
 *
 * @package    PACMEC
 * @category   WompiCO
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace WompiCO;

class WompiSyncHistory extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'wompi_sync';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
  }

  public function create()
  {
  	$columns = $this->getColumns();
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
    		if(isset($this->{$i})){
    			$columns_f[] = $i;
    			$columns_a[] = "?";
    			if($i == 'data'){
    				$items_send[] = json_encode($this->{$i});
    			} else {
    				$items_send[] = $this->{$i};
    			}
    		}
    	}
      $sql = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` (".implode(',', $columns_f).") VALUES (".implode(",", $columns_a).")";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, $items_send);
      if($insert>0){
      	$this->id = $insert;
      	return $insert;
      }
      return 0;
  	}catch (Exception $e){
  		return 0;
  	}
  }

}
