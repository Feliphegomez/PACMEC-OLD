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

class Notifications extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME                = 'notifications';
  const COLUMNS_AUTO_T            = [];

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
      if(!empty($this->data)) $this->data = json_decode($this->data);
      if(empty($this->link_view))  $this->link_view = "#";
    }
  }

  public static function get_all_by_user_id($user_id=null, ...$includes) : Array
  {
    try {
      global $PACMEC;
      if($user_id == null) $user_id = \userID();
      $include_read = (isset($includes[0]) && $includes[0] == true) ? "" : " AND `is_read` IN ('0') ";
      $include_limit = (isset($includes[1]) && is_numeric($includes[1])) ? " LIMIT {$includes[1]}" : " LIMIT 500 ";
      $r = [];
      $sql = "Select * from `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE `user_id`=? AND `host` IN ('*', ?) $include_read ORDER BY `host` desc {$include_limit} ";
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

  public static function table_list_html($items) : String
  {
    global $PACMEC;
    $table = \PHPStrap\Table::borderedTable();
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      __a('notification'), __a('created'), ''
    ]);
    foreach ($items as $uid => $item) {
      switch ($item->is_read) {
        case 1:
          $icon = \PHPStrap\Util\Html::tag('a',
            \PHPStrap\Util\Html::tag('i', '', ['fa fa-check-circle'], ["id"=>'pacmec-change-status-notification-fast-icon-'.$item->id])
          , ['pacmec-change-status-notification-fast'], ['href'=>'#', 'data-notification_id'=>$item->id]);
          break;
        case 0:
        $icon = \PHPStrap\Util\Html::tag('a',
          \PHPStrap\Util\Html::tag('i', '', ['fa fa-dot-circle-o'], ["id"=>'pacmec-change-status-notification-fast-icon-'.$item->id])
        , ['pacmec-change-status-notification-fast'], ['href'=>'#', 'data-notification_id'=>$item->id]);
          break;
        default:
          $icon = \PHPStrap\Util\Html::tag('a',
            \PHPStrap\Util\Html::tag('i', '', ['fa fa-circle-o'], ["id"=>'pacmec-change-status-notification-fast-icon-'.$item->id])
          , ['pacmec-change-status-notification-fast'], ['href'=>'#', 'data-notification_id'=>$item->id]);
          break;
      }
      $table->addRow([
        \PHPStrap\Util\Html::tag('a',
          (!empty($item->title) ? (\PHPStrap\Util\Html::tag('b', $item->title)."<br />") : "")
          . $item->message
        , [], ['href'=>$item->link_view, "target"=>($_SERVER['SERVER_NAME']==$PACMEC['host'] ? '_self' : "_blank")])
        , $item->created
        , $icon
      /*
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
      */
      ]);
    }
    return $table;
  }

  public function get_by_id($notification_id=null, $isMe=true)
  {
    $_isMe = $isMe==true ? " AND `user_id`={\userID()} " : "";
    $sql = "Select * from `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE `id`=? AND `host` IN ('*', ?) $_isMe ORDER BY `host` desc {$include_limit} ";
    $result = Self::link()->FetchObject($sql, [$notification_id, $PACMEC['host']]);
    $this->set_all($result);
    return $this;
  }

  private function change_status($new_status=1)
  {
    $sql = "UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET `is_read`=? WHERE `id`=? AND `user_id`=? AND `host` IN ('*', ?)";
    $result = Self::link()->FetchObject($sql, [$new_status, $this->id, \userID(), $GLOBALS['PACMEC']['host']]);
    if($result==true) $this->is_read = $new_status;
    return $result;
  }

  public function read()
  {
    return $this->change_status(1);
  }

  public function unread()
  {
    return $this->change_status(0);
  }
}
