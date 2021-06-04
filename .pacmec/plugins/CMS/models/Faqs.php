<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CMS
 * @license    license.txt
 * @version    1.0.1
 */

namespace PACMEC\CMS;

Class Faqs extends \PACMEC\System\ModeloBase {
  const MODEL_TBL = 'faqs';
  const COLUMNS_AUTO_T = [];
  public $question = "";
  public $question_r = "";
  //formatSchedule($service->execution_time, $service->execution_cycle);

	public function __construct($args=[])
  {
		$args = (array) $args;
		parent::__construct('faqs', true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
	}

  public static function byIds($services_ids=[])
  {
    $r = [];
    foreach ($services_ids as $id) {
      $r[] = new Self(["id"=>$id->service]);
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
      "question" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "faqs",
          "question",
          $this->query,
        ]
      ],
      "question_r" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "faqs",
          "response",
          $this->query,
        ]
      ],
    ] as $key => $atts) {
      if(property_exists($this, $key)){
        $s = implode($atts["s"], $atts["parts"]);
        $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
      }
    }
  }

	public static function allLoad($limit=null) : array
  {
		$r = [];
    if($limit==null){
      $sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` ";
    } else {
      $sql = "SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}" . SELF::MODEL_TBL . "` LIMIT {$limit}";
    }
		foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $menu){
			$r[] = new Self($menu);
		}
		return $r;
	}
}
