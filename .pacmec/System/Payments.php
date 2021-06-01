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

class Payments extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'payments';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->payment_id)) $this->get_by_id($opts->payment_id);
    elseif(is_object($opts) && isset($opts->payment_ref)) $this->get_by('ref', $opts->payment_ref);
  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->data = json_decode($this->data);
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

  public static function table_list_html(array $items) : String
  {
    $table = \PHPStrap\Table::borderedTable();
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      ''
      , __a('order_ref')
      , 'ID TX'
      , __a('payment_date')
      , __a('status')
      , __a('total_paid')
    ]);
    foreach ($items as $i => $payment) {
      $i++;
      $btns = "";
      $explode_ref = explode(':', \decrypt($payment->data->transaction->reference, \infosite('pim_hash_tx')));
      $order_id = null;
      if(isset($explode_ref[2])){
        if($explode_ref[0] == 'order_id'){
          $order_id = $explode_ref[1];
        }
      }
      //echo json_encode($payment)."\n";
      $table->addRow([
        $i
        , \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('b', $order_id), [], ['href' => __url_S("/%order_view%/".urlencode($order_id))])
        , $payment->transaction_id
        , $payment->created
        , $payment->status
        , formatMoney($payment->amount_payment)
      ]);
      /*
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-eye']), ['btn btn-sm btn-outline-success btn-hover-success'], [ 'href'=>$order->link_view ]);
      $table->addRow([
        $btns
        , \PHPStrap\Util\Html::tag('a',
          \PHPStrap\Util\Html::tag('b', $order)
        , [], ['href'=>$order->link_view])
        , $order->status
        // , $order->ref
        // , "$order->total_products"
        // , "$order->subtotal"
        // , ($order->discounts)
        , formatMoney($order->total-$order->payment_total)
        , \time_passed($order->created)
        , ($order->pay_enabled==true)
            ? \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-usd']) . ' Pagar ahora ', ['btn btn-sm btn-outline-primary btn-hover-primary'], ['href'=>$order->link_pay])
            : ($order->payment_date!== null ? \time_passed($order->payment_date) : '-')
        // , \PHPStrap\Util\Html::tag('a', 'View', ['btn btn-sm btn-outline-dark btn-hover-primary'], ['href'=>$order->link_view])
        // , \time_passed(date('m-d-Y H:i:s', strtotime($order->created)))
        //, \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-comment']), ['btn btn-sm btn-outline-secondary btn-hover-dark'], ['href'=>$order->link_view])
      ]);*/
    }
    return $table;
  }

  public static function get_all_by_user_id($user_id=null) : Array
  {
    try {
      if($user_id == null) $user_id = \userID();
      $r = [];
      $sql = "SELECT ORDSTX.*
      FROM `" . Self::link()->getTableName("users_".\PACMEC\System\Orders::TABLE_NAME) . "` ORDUS
      INNER JOIN `" . Self::link()->getTableName(\PACMEC\System\OrdersTx::TABLE_NAME) . "` ORDSTX
      ON ORDSTX.order_id = ORDUS.order_id
      WHERE ORDUS.user_id IN (?)";
			$result = Self::link()->FetchAllObject($sql, [$user_id]);
      if($result !== false){
        foreach ($result as $item) {
          $a = new Self((object) ['payment_id'=>$item->id]);
          $r[] = $a;
        }
      }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

}
