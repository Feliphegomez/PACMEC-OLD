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

class Orders extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME                = 'orders';
  public $link_view               = "#";
	public $link_pay                = "#";
  public $total                   = 0.00;
  public $subtotal                = 0.00;
  public $discounts               = 0.00;
  public $payments                = [];
  public $payment_total           = 0.00;
  public $payment_date            = null;
  public $pay_enabled             = false;
  public $counting                = 0;
  public $items                   = [];
  public $addresses               = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    $this->status = \infosite('pim_orders_status_default');
    if(isset($opts->order_id)) $this->get_by_id($opts->order_id);
    elseif(isset($opts->order_ref)) $this->get_by('ref', urldecode($opts->order_ref));
  }

  public function __toString() : String
  {
    return "ORD-".\zfill($this->id, 5);
  }

  public function set_all($obj)
  {
    global $PACMEC;
    Parent::set_all($obj);
    if($this->isValid()){
      $this->link_view = infosite('siteurl').__url_S("/%order_view%/".urlencode($this->ref));
      #$this->link_pay = __url_S("/%order_pay%/".urlencode($this->ref));
      $this->link_pay = "#";
      $payment_provider = infosite('payment_provider');
      $this->status = new \PACMEC\System\OrdersStatus((object) ['id'=>$this->status]);
      if(isset($this->status->pay_enabled) && $this->status->pay_enabled == 1) $this->pay_enabled = true;
      $this->get_items_this();
      $this->get_pays_this();
      $this->get_addresses_this();
      if($this->pay_enabled == true && $this->payment_total >= $this->total) $this->pay_enabled = false;
      if(isset($PACMEC['gateways']['payments']->gateways[$payment_provider])){
        $this->link_pay = $PACMEC['gateways']['payments']->gateways[$payment_provider]->get_url_checkout((($this->total-$this->payment_total)*100), "order_id:{$this->id}");
      }
    }
    ###echo json_encode($this, JSON_PRETTY_PRINT);
    ###exit;
  }

  public function get_addresses_this()
  {
    $items = \PACMEC\System\GeoAddresses::get_all_by_order_id($this->id);
    foreach ($items as $item) {
      $this->addresses[] = $item;
    }
  }

  public function get_pays_this()
  {
    $items = \PACMEC\System\OrdersTx::get_by_orderid($this->id);
    foreach ($items as $item) {
      $this->payment_date = $item->tx->updated;
      $this->payment_total += $item->tx->amount_payment;
      $this->payments[] = $item;
    }
  }

  public function get_items_this()
  {
    $items = \PACMEC\System\OrdersItems::get_by_orderid($this->id);
    foreach ($items as $item) {
      if($item->isValid() && $item->data !== null){
        switch ($item->type) {
          case 'product':
            $this->subtotal += $item->subtotal;
            //$this->discounts += $item->discounts;
            $this->items[] = $item;
            break;
          case 'discount':
            $item->description .= $item->discounts;
            $this->items[] = $item;
            break;
          case 'coupon':
            $this->items[] = $item;
            break;
          default:
            // NO DETECTADO NO SE AGREGA NI SE SUMA NI SE RESTA
            break;
        }
      }
    }
    foreach ($this->items as $item) {
      if(!empty($item->discount_percentage)) $item->discounts += ($this->subtotal*$item->discount_percentage)/100;
      if(!empty($item->discount_amount)) $item->discounts += $item->discount_amount;
      $this->discounts += $item->discounts;
      $item->total =$item->subtotal - $item->discounts;
    }
    $this->counting = count($this->items);
    $this->total = $this->subtotal - $this->discounts;
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
      ]);
    }
    return $table;
  }

  public function change_status($status_id)
  {
    try {
      $sql = "UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET `status`=? ";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$status_id]);
      if($insert>0){
        $this->status = new \PACMEC\System\OrdersStatus((object) ['id'=>$status_id]);
        return $insert;
      }
    } catch (\Exception $e) {
      return 0;
    }

  }

  public function isMe($user_id=null)
  {
    try {
      if($user_id == null) $user_id = \userID();
      $sql = "Select * from `".$this::get_table_users_in()."` WHERE `user_id`=? and `order_id`=?";
			$result = $this::link()->FetchObject($sql, [$user_id, $this->id]);
      return isset($result->id) && $result->id>0 ? true : false;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return false;
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
            , 'id_alt'
            , 'identification_type'
            , 'identification_number'
            , 'names'
            , 'surname'
            , 'company_name'
            , 'email'
            , 'phone'
            , 'mobile'
            , 'obs'
            , 'customer_ip'
          ])
        ){
    			$columns_f[] = $i;
    			$columns_a[] = "?";
    			$columns_b[] = " `{$i}`=? ";
    			$items_send[] = $this->{$i};
    		}
    	}

      #echo json_encode($columns_f)."\n";
      #echo json_encode($this)."\n";

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
      	$this->id = $insert->id;
        $this->ref = \encrypt("order_id:{$this->id}:".infosite('wompi_mode').":user_id:".\userID(), \infosite('pim_hash_tx'));
        $upd_sql = "UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET ".implode(",", array_merge($columns_b, ['ref=?'])) . " WHERE `id`='{$this->id}'";
        $insert = $GLOBALS['PACMEC']['DB']->FetchObject($upd_sql, array_merge($items_send, [$this->ref]));
        if($insert!==false && $insert > 0){
          $this->get_by_id($this->id);
          if(\isUser()){ $this->add_in_user(); }
      	  return $this->id;
        }
      }
      return 0;
  	}catch (Exception $e){
      echo json_encode($e->getMessage());
  		return 0;
  	}
  }

  public function add_in_user($user_id=null)
  {
    try {
      if($user_id == null) $user_id = \userID();
      $sql = "INSERT INTO `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` (`order_id`, `user_id`)
      SELECT ?, ? WHERE NOT EXISTS (SELECT 1 FROM `".Self::link()->getTableName("users_".Self::TABLE_NAME)."` WHERE `order_id`=? AND `user_id`=?) ";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->id, $user_id,$this->id, $user_id]);
      if($insert>0){
        return $insert;
      }
      return 0;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return 0;
    }
  }
}
