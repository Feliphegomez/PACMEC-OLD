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

class OrdersTx extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'orders_tx';

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(
      is_object($opts)
      && isset($opts->id)
    ) {
      $this->get_by_id($opts->id);
    }
  }

  public static function get_by_orderid($order_id) : array
  {
    try {
      return Self::get_all_by('order_id', $order_id);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->tx = new \PACMEC\System\Payments((object) ["payment_id" => $this->tx]);
    }
  }

  public function create()
  {
  	try {
      $sql    = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` (`order_id`, `tx`) VALUES (?, ?)";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->order_id, $this->tx]);
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
