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

class Coupons extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'coupon_codes';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->coupon_code)) $this->get_by('code', $opts->coupon_code);
    elseif(is_object($opts) && isset($opts->id)) $this->get_by('id', $opts->id);
  }

  public function __toString() : string
  {
    return "{$this->code} :: $ {$this->amount}";
  }

  // CREAR COBRO DE CUPON
  public function redeemed()
  {
    if($this->redeemed_date !== null) return false;
    $datetime1 = date_create('now');
    $sql = "UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET `redeemed_date`=? WHERE `id`=?";
    $result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$datetime1->format('Y-m-d H:i:s'), $this->id]);
    return ($result);
  }

}
