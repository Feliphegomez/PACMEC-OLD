<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */

namespace PACMEC\CRM;

Class ServicesCharacteristics extends \PACMEC\System\ModeloBase {
  const MODEL_TBL = 'services_characteristics';
  const COLUMNS_AUTO_T = [];
  public $name = "";
  public $description = "";
  public $description_short = "";

	public function __construct($args=[])
  {
		$args = (array) $args;
		parent::__construct(SELF::MODEL_TBL, true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
	}

  public static function byIds($ids=[], $limit=null)
  {
    $r = [];
    $i = 0;
    foreach ($ids as $id) {
      $i++;
      if($limit!==null&&$limit==$i) break;
      $r[] = new Self(["id"=>is_numeric($id)?$id:(isset($id->service)?$id->service:0)]);
    }
    return $r;
  }

  public static function byId($id)
  {
    return new Self(["id"=>$id]);;
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
      "name" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "services",
          "characteristics",
          $this->id,
        ]
      ],
      "description" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "services",
          "characteristics",
          "description",
          $this->id,
        ]
      ],
      "description_short" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "services",
          "characteristics",
          "description",
          "short",
          $this->id,
        ]
      ]
    ] as $key => $atts) {
      if(property_exists($this, $key)){
        $s = implode($atts["s"], $atts["parts"]);
        $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
      }
    }
  }

  public static function allLoad($limit=null) : array
  {
    $add_sql = "";
    $r = [];
    if($limit==null){
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` ".$add_sql;
    } else {
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` {$add_sql} LIMIT {$limit}";
    }
    foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item){
      $r[] = new Self(['id'=>$item->id]);
    }
    return $r;
  }


  public static function byService($id=null) : array
  {
    $add_sql = "";
    $r = [];
    $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` WHERE `service` IN (?) ";
    foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, [$id]) as $item){
      $r[] = new Self(['id'=>$item->id]);
    }
    return $r;
  }


}
