<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   Memberships
 * @license    license.txt
 * @version    1.0.1
 */

namespace PACMEC\AMS;

Class Memberships extends \PACMEC\System\ModeloBase {
  const DAYS_LABELS     = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
  const MODEL_TBL       = 'memberships';
  const COLUMNS_AUTO_T  = [];
  public $name          = "";
  public $total_payment = -1;
  public $features = [];
  public $services = [];

	public function __construct($args=[])
  {
		$args = (array) $args;
		parent::__construct(SELF::MODEL_TBL, true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
	}

	public static function list_days(){
		return SELF::DAYS_LABELS;
	}

  public static function byIds($memberships_ids=[])
  {
    $r = [];
    foreach ($memberships_ids as $id) {
      $r[] = new Self(["id"=>(is_numeric($id->service)?$id->service:(isset($id->service)?$id->service:0))]);
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
      "name" => [
        "s"      => "_",
        "autoT"  => true,
        "parts"  => [
          "memberships",
          $this->id,
        ]
      ],
      "initial_payment" => [
        "s"      => "",
        "autoT"  => false,
        "parts"  => [
          ($this->initial_payment),
        ]
      ],
      "billing_amount" => [
        "s"      => "",
        "autoT"  => false,
        "parts"  => [
          ($this->billing_amount),
        ]
      ],
      "total_payment" => [
        "s"      => "",
        "autoT"  => false,
        "parts"  => [
          ($this->initial_payment+$this->billing_amount),
        ]
      ],
    ] as $key => $atts) {
      if(property_exists($this, $key)){
        $s = implode($atts["s"], $atts["parts"]);
        $this->{$key} = ($atts["autoT"] == true) ? _autoT($s) : $s;
      }
    }
    if($this->isValid()){
      $this->services = Self::load_services_by('membership', $this->id);
      $this->features = Self::load_feature_by('membership', $this->id);
    }
  }

	public static function allLoad($limit=null, $allow_signups=true) : array
  {
    $add_sql = ($allow_signups==false) ? "" : ' WHERE `allow_signups` IN (1)';
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

	public static function load_signups_plans($limit=3) : array
  {
		try {
			return Self::allLoad($limit);
		}
		catch(\Exception $e){
			return [];
		}
	}

  public static function load_services_by($lkey, $search=0) : array
  {
		try {
			$sql = "Select * FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}memberships_services` WHERE `{$lkey}` IN (?) ";
			$result = $GLOBALS['PACMEC']['DB']->FetchAllObject($sql, [$search]);
			$result = $result !== false ? $result : [];
			$r = [];
			foreach($result as $a){
				$a->service = \PACMEC\CRM\Services::byId($a->service);
				$r[] = $a;
			}
			return $r;
		}
		catch(\Exception $e){
			return [];
		}
	}

  public static function load_feature_by($lkey, $search=0)
  {
		try {
      $sql = "Select * FROM `{$GLOBALS['PACMEC']['DB']->getPrefix()}memberships_features` WHERE `{$lkey}` IN (?) ";
      $result = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$search]);
      $result = $result !== false ? $result :(object) [];
      if(isset($result->id)) $result->category = new \PACMEC\Categories(['id'=>$result->category]);
      if(isset($result->feature_parent)) $result->feature_parent = Self::load_feature_by('id', $result->feature_parent);
      return $result;
		}
		catch(\Exception $e){
			return (object) [];
		}
	}

}
