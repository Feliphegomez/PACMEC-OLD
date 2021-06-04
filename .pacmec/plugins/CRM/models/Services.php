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

Class Services extends \PACMEC\System\ModeloBase {
  const MODEL_TBL = 'services';
  const COLUMNS_AUTO_T = [];
  public $name = "";
  public $description = "";
  public $description_short = "";
  public $characteristics = [];

	public function __construct($args=[])
  {
		$args = (array) $args;
		parent::__construct('services', true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
	}

  public static function byIds($services_ids=[], $limit=null)
  {
    $r = [];
    $i = 0;
    foreach ($services_ids as $id) {
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
          $this->id,
        ]
      ],
      "description" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "services",
          "description",
          $this->id,
        ]
      ],
      "description_short" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "services",
          "description_short",
          $this->id,
        ]
      ]
    ] as $key => $atts) {
      if(property_exists($this, $key)){
        $s = implode($atts["s"], $atts["parts"]);
        $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
      }
    }
    if($this->isValid()){
      $this->characteristics =  \PACMEC\CRM\ServicesCharacteristics::byService($this->id);
    }
    if(empty($this->thumb)) $this->thumb = infosite('sitelogo');
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


}
