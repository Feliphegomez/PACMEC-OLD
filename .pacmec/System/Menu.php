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

class Menu extends ModeloBase {
	private $prefix = null;
	private $db = null;
	public $id;
	public $name;
	public $slug;
	public $permission_access;
	public $items = [];

	public function __construct($args=[]){
		$args = (array) $args;
		parent::__construct("menus", true);
		if(isset($args['by_id'])) $this->getBy('id', $args['by_id']);
		if(isset($args['by_slug'])) $this->getBy('slug', $args['by_slug']);
	}

	public function getBy($column='id', $val=""){
		try {
			return $this->setAll($GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$this->getTable()}` WHERE `{$column}`=?", [$val]));
		}
		catch(Exception $e){
			return $this;
		}
	}

	public function setAll($arg=[]){
		$arg = (array) $arg;
		foreach($arg as $k=>$v){
			if(isset($this->{$k})){
				switch ($k) {
					default:
						$this->{$k} = $v;
						break;
				}
			}
		}
		if($this->isValid()){
			$this->name = __a($this->name);
			$this->items = $this->loadItemsMenu($this->id);
		}
		return $this;
	}

	public static function validatePermission($item) : bool
	{
		if($item->permission_access !== null && !empty($item->permission_access)){
			return \validate_permission($item->permission_access);
		}
    if($item->guests == 1 && \isUser()) return false;
    if($item->users == 1 && !\isUser()) return false;
		return true;
	}

	private function loadItemsMenu($id=0, $parent = 0)
  {
    global $PACMEC;
		$r = [];
		foreach($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM {$GLOBALS['PACMEC']['DB']->getPrefix()}menus_elements WHERE `menu`=? AND `index_id`=? ORDER BY `ordering`", [$id,$parent]) as $item){
			if(Self::validatePermission($item)){
        $item->title       = \__a($item->title);
        $item->tag_href = __url_s($item->tag_href);
				$childs = $this->loadItemsMenu($id, $item->id);
				$item->childs = [];
				if($childs !== false){
					foreach ($childs as $index => $child) {
						$child->tag_href = Route::encodeURIautoT($child->tag_href);
					}
					$item->childs = $childs;
				}
				$r[] = $item;
			}
		}
		return $r;
	}

	public static function allLoad() : array {
		$r = [];
		foreach($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$this->getTable()}` ", []) as $menu){
			$r[] = new Self($menu);
		}
		return $r;
	}
}
