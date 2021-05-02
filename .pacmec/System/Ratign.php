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

class Ratign extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME            = 'comments';
  const COLUMNS_AUTO_T        = [];
	public $sum                 = 0;
	public $count               = 0;
	public $max                 = 0;
	public $min                 = 0;
  public $rating_number       = 0;
	public $rating_porcen       = 0;
	public $votes               = [];

  public function __construct($opts=null)
  {
    //Parent::__construct();
    if(is_object($opts) && isset($opts->path_or_uri)) $this->get_by('uri', $opts->path_or_uri);
  }

  public function __toString() : string
  {
    return json_encode($this);
  }

  public static function get_all_uri($uri, $loadvotes=true)
  {
    try {
      $r = new Self();
      $sql = "SELECT SUM(`vote`) AS `sum`
        , COUNT(`vote`) AS `count`
        , MAX(`vote`) AS `max`
        , MIN(`vote`) AS `min`
        , AVG(`vote`) AS `rating_number`
      FROM `".Self::get_table()."`
      WHERE `uri`=?";
			$result = Self::link()->FetchObject($sql, [$uri]);
      if($result !== false){
        $r->set_all($result);
        if($loadvotes==true) $r->votes = \PACMEC\System\Comments::get_all_by('uri', $uri);
      }
      return $r;
    } catch (\Exception $e) {
      echo $e->getMessage();
      return new Self;
    }
  }

  public function set_all($obj)
  {
    global $PACMEC;
    if($obj->count>0){
      foreach ($obj as $k => $v) {
        $this->{$k} = $v;
      }
      if($this->rating_number>0){
        $this->rating_porcen = (int) (($this->rating_number*100)/$this->max);
      }
    }
  }
}
