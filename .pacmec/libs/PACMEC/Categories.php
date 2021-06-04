<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */

namespace PACMEC;

Class Categories extends System\ModeloBase {
  const MODEL_TBL = 'categories';
  const COLUMNS_AUTO_T = [];
  public $name = "";
  public $price_range = "";
  public $childs = [];

	public function __construct($args=[])
  {
		$args = (array) $args;
		parent::__construct('categories', true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
	}

	public static function allLoad($tree=false, $index_id=null) : array
  {
		$r = [];
    if($tree==true && $index_id !== null){
      $cts = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` WHERE `index_id` IN (?) ", [$index_id]);
      foreach($cts as $ct){
        $it = new Self($ct);
        $it->childs = Self::allLoad($tree, $ct->id);
        $r[] = $it;
  		}
    } else {
      $sql = $index_id!==null ? "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` WHERE `index_id` IN (?) " : "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` ";
      foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, ($index_id!==null ? [$index_id] : [])) as $ct){
  			$r[] = new Self($ct);
  		}
    }
		return $r;
	}

  public function setAll($array = [])
  {
    $array = (object) $array;
    foreach(array_keys($this->getLabels()) as $label) {
      if(isset($array->{$label})) {
        if(in_array($label, SELF::COLUMNS_AUTO_T)) {
          $this->{$label} = \_autoT($array->{$label});
        } else {
          $this->{$label} = $array->{$label};
        }
      }
    }
    foreach ([
      "name" => [
        "s"      => "",
        "autoT"  => true,
        "parts"  => [
          "categories_",
          $this->id,
        ]
      ],
      "price_range" => [
        "s"      => " - ",
        "autoT"  => false,
        "parts"  => [
          \formatMoney($this->price_min),
          \formatMoney($this->price_max)
        ]
      ]
    ] as $key => $atts) {
      if(property_exists($this, $key)){
        $s = implode($atts["s"], $atts["parts"]);
        $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
      }
    }
  }

}
