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

class OrdersStatus extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'orders_status';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by_id($opts->id);
  }

  public function __toString() : string
  {
    return isset($this->name) ? $this->name : 'undefined';
  }
}
