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

class OrdersItems extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'orders_items';
  public $name                = "";
  public $description         = "";
  public $total               = 0.00;
  public $subtotal            = 0.00;
  public $discounts           = 0.00;
  public $unid                = '';
  public $data                = null;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(
      is_object($opts)
      && isset($opts->id)
      && isset($opts->order_id)
      && isset($opts->type)
      && isset($opts->ref)
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
      switch ($this->type) {
        case 'product':
          $product = new \PACMEC\System\Product((object) ["id"=>$this->ref]);
          if($product->isValid()){
            $this->name = $product->name;
            $this->description = __a('sku_ref') . ": " . $product->sku;
            $this->data = $product;
            if($this->unit_price == null) $this->unit_price = $product->price;
            $this->subtotal = $this->unit_price * $this->quantity;
            $this->unid = $product->unid;
            if(!empty($this->discount_amount)) $this->discounts += $this->discount_amount;
            if(!empty($this->discount_percentage)) $this->discounts += ($this->subtotal*$this->discount_percentage)/100;
          }
          break;
        case 'service':
          $service = new \PACMEC\System\Service((object) ["id"=>$this->ref]);
          if($service->isValid()){
            $this->name = $service->name;
            $this->description = __a('slug_ref') . ": " . $service->slug;
            $this->data = $service;
            if($this->unit_price == null) $this->unit_price = $service->price;
            $this->subtotal = $this->unit_price * $this->quantity;
            $this->unid = $service->unid;
            if(!empty($this->discount_amount)) $this->discounts += $this->discount_amount;
            if(!empty($this->discount_percentage)) $this->discounts += ($this->subtotal*$this->discount_percentage)/100;
          }
          break;
        case 'discount':
          $this->name = $obj->ref;
          $this->description = "$ ";
          $this->data = $obj;
          break;
        case 'coupon':
          $coupon = new \PACMEC\System\Coupons((object) ["id"=>$this->ref]);
          if($coupon->isValid() && $coupon->redeemed_date !== null){
            $this->name = __a('coupon_code').": {$coupon->code}";
            $this->description = "$ {$coupon->amount}";
            $this->discount_amount = $coupon->amount;
            $this->discounts += $coupon->amount;
            $this->data = $coupon;
          }
          break;
        default:
          break;
      }
    }
  }

  public function create()
  {
    global $PACMEC;
    $keys_a = $keys = [
      'order_id' => $this->order_id,
      'type' => $this->type,
      'ref' => $this->ref,
    ];
    if(!empty($this->quantity)) $keys['quantity'] = $this->quantity;
    if(!empty($this->unit_price)) $keys['unit_price'] = $this->unit_price;
    if(!empty($this->discount_percentage)) $keys['discount_percentage'] = $this->discount_percentage;
    if(!empty($this->discount_amount)) $keys['discount_amount'] = $this->discount_amount;
    $qs = [];
    for ($i=0; $i < count($keys); $i++) {
      $qs[] = '?';
    }
    $keys_ = array_merge(array_values($keys), array_values($keys_a));
    $sql = "INSERT INTO `{$PACMEC['DB']->getTableName(SELF::TABLE_NAME)}` (`".implode('`, `', array_keys($keys))."`) SELECT ".implode(',', $qs)." WHERE NOT EXISTS (SELECT 1 FROM `{$PACMEC['DB']->getTableName(SELF::TABLE_NAME)}` WHERE `order_id`=? AND `type`=? AND `ref`=?)";

    $result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, array_values($keys_));
    $sql_s = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE `order_id`=? AND `type`=? AND `ref`=?";
    $select = $GLOBALS['PACMEC']['DB']->FetchObject($sql_s, array_values($keys_a));
    if($select!==false && $select->id > 0){
      $this->get_by_id($select->id);
      return $select->id;
    }
    return 0;
  }

  public function add_in_order($order_id, $type, $ref, $quantity, $unit_price=null, $discount_percentage=null, $discount_amount=null){
    global $PACMEC;
    //$sql = "INSERT INTO `{$PACMEC['DB']->getTableName(SELF::TABLE_NAME)}` (`order_id`, `type`, `ref`, `discount_amount`) VALUES (?,?,?,?)";
  }
}
