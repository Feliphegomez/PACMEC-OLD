<?php
/**
 *
 * @package    PACMEC
 * @category   PIM
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PIM;

class Filters extends \PACMEC\System\ModeloBase {
  const SERVER_GALLERY  = 'https://famaymoda.com';
  const MODEL_TBL       = 'mt_products_filters';
  const COLUMNS_AUTO_T  = [];

	public function __construct($args=[]){
		$args = (array) $args;
		parent::__construct(SELF::MODEL_TBL, true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
    return $this;
	}
    public static function allInUsed($limit=null) : array
    {
      $sql = "SELECT F.*, PF.`text`, COUNT(PF.`id`) AS `total_count`
        FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` PF
      	INNER JOIN 	`{$GLOBALS['PACMEC']['DB']->getTableName('mt_products_features')}` F
      	ON F.`id` = PF.`feature`
      	GROUP BY PF.`feature`, PF.`text`
      	ORDER BY F.`name`, PF.`text` ASC;";
      foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item){
        $r[] = new Self(['id'=>$item->id]);
      }
      return $r;
    }

    public function setAll($array = [])
    {
      $array = (object) $array;
      foreach(array_keys($this->getLabels()) as $label) {
        if(isset($array->{$label})) {
          $this->{$label} = $array->{$label};
        }
      }
      foreach ([
        /*
        "name" => [
          "s"      => "",
          "autoT"  => false,
          "parts"  => [
            $this->name,
          ]
        ],*/
      ] as $key => $atts) {
        if(property_exists($this, $key)){
          $s = implode($atts["s"], $atts["parts"]);
          $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
        }
      }
      if($this->isValid()){
      }
    }
}
