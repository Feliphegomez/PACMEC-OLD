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

class WompiStatus extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'orders_status';
  const COLUMNS_AUTO_T        = [];

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->status_wompi)) $this->get_by('status_wompi', $opts->status_wompi);
  }
}
