<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   eMailsBoxes
 * @license    license.txt
 * @version    1.0.1
 */

namespace PACMEC\ERP;

Class eMailsBoxes extends \PACMEC\System\BaseRecords {
  const TABLE_NAME       = 'emails_boxes';
  const COLUMNS_AUTO_T  = [];

	public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by('id', $opts->id);
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
      "label" => [
        "s"      => "_",
        "autoT"  => false,
        "parts"  => [
          $this->label,
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
    if($limit==null){ $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` "; }
    else { $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` LIMIT {$limit}"; }
		foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item){
			$r[] = new Self(['id'=>$item->id]);
		}
		return $r;
	}

  public static function load_users_by($lkey, $search=0) : array
  {
		try {
			$sql = "Select * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('emails_users')}` WHERE `{$lkey}` IN (?)";
			$result = $GLOBALS['PACMEC']['DB']->FetchAllObject($sql, [$search]);
			$result = $result !== false ? $result : [];
			$r = [];
			foreach($result as $a){
         $b = new Self((object) ['id'=>$a->box_id]);
         if($b->actived == 1 && $b->id>0) $r[] = $b;
			}
			return $r;
		}
		catch(\Exception $e){
			return [];
		}
	}

	public function save($info_save)
	{
		try {
			$id = $this->id;
			$labels = [];
			$values = [];
			foreach ($info_save as $key => $value) {
				$labels[] = "{$key}=?";
				$values[] = $value;
			}
			$result = $GLOBALS['PACMEC']['DB']->FetchObject("UPDATE IGNORE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET ".implode(',', $labels)." WHERE `id`={$id}", $values);
			if($result==true) {
				return true;
			};
			return false;
		}
		catch(Exception $e){
			#echo $e->getMessage();
			return false;
		}
	}

}
