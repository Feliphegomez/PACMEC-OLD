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

class Notifications extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'notifications';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by('id', $opts->id);
  }

  public function __toString() : string
  {
    return json_encode($this);
  }

  public function set_all($obj)
  {
    global $PACMEC;
    Parent::set_all($obj);
    if($this->isValid()){
      $this->data = json_decode($this->data);
    }
  }

  public static function get_all_by_user_id($user_id=null, ...$includes) : Array
  {
    try {
      global $PACMEC;
      if($user_id == null) $user_id = \userID();
      $include_read = (isset($includes[0]) && $includes[0] == true) ? "" : " AND `is_read` IN ('0') ";
      $include_read = (isset($includes[1]) && is_numeric($includes[1])) ? " LIMIT {$includes[1]}" : " LIMIT 500 ";
      $r = [];
      $sql = "Select * from `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE `user_id`=? AND `host` IN ('*', ?) $include_read ORDER BY `host` desc";
			$result = Self::link()->FetchAllObject($sql, [$user_id, $PACMEC['host']]);
      if($result !== false){
        foreach ($result as $item) {
          $a = new Self($item);
          $r[] = $a;
        }
      }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return [];
    }
  }

  public static function table_list_html(array $items) : String
  {
    $table = \PHPStrap\Table::borderedTable();
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      ''
      , 'ID'
      , __a('status')
      // , 'ref'
      // , 'items'
      // , 'subtotal'
      // , 'descuentos'
      , __a('outstanding_balance')
      , __a('created')
      , __a('payment_date')
    ]);
    foreach ($items as $order) {
      $btns = "";
      //$btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-eye']), ['btn btn-sm btn-outline-success btn-hover-success'], [ 'href'=>$order->link_view ]);
      /*
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
      ]);
      */
    }
    return $table;
  }
}
