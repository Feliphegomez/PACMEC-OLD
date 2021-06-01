<?php
/**
 *
 * @package    PACMEC
 * @category   PIM
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace PACMEC\System;

class Products extends \PACMEC\System\ModeloBase {
  const SERVER_GALLERY  = 'https://famaymoda.com';
  const MODEL_TBL       = 'products';
  const COLUMNS_AUTO_T  = [];
	public $in_promo      = false;
	public $link_view     = "#";
	public $thumb         = "";
	public $gallery       = [];
	public $features      = [];
	public $rating_number = 0;
	public $rating_porcen = 0;

	public function __construct($args=[]){
		$args = (array) $args;
		parent::__construct(SELF::MODEL_TBL, true);
		if(isset($args['id'])) $this->getBy('id', $args['id']);
    return $this;
	}

	public function getBy($column='id', $val=""){
		try {
			return $this->setAll($GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` WHERE `{$column}`=?", [$val]));
		}
		catch(Exception $e){
			return $r;
		}
	}

  public static function byId($id)
  {
    $add_sql = " WHERE `is_active` IN (1) ORDER BY `created` DESC";
    $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` WHERE `id` IN (?) ".$add_sql;
    $item = $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$id]);
    if($item->id > 0) return new Product((object) ['id'=>$item->id]);
    else return new Product;
  }

  public static function prices_min_max()
  {
    $sql = "SELECT MIN(`price_normal`) AS `price_min`, MAX(`price_normal`) AS `price_max` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` ";
    return $GLOBALS['PACMEC']['DB']->FetchObject($sql, []);
  }

  public static function allNews($limit=null) : array
  {
    $in_gallery_sql_p_w = "";
    $in_gallery_sql_p_a = "";
    if(\infosite('with_gallery')==true) {
      $sql_gallery = "SELECT GROUP_CONCAT(PP.`product`) as ids
        FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` PP
        INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
        WHERE P.is_active = 1 AND P.available > 0";
      $in_gallery = $GLOBALS['PACMEC']['DB']->FetchObject($sql_gallery, []);
      $in_gallery_sql_p_w = " WHERE `id` IN ({$in_gallery->ids}0) ";
      $in_gallery_sql_p_a = " AND `id` IN ({$in_gallery->ids}0) ";
    }
    $add_sql = " WHERE `is_active` IN (1) {$in_gallery_sql_p_a} ORDER BY `created` DESC";
    $r = [];
    if($limit==null){
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` ".$add_sql;
    } else {
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` {$add_sql} LIMIT {$limit}";
    }
    foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item){
      $r[] = new Product((object) ['id' => $item->id]);
    }
    return $r;
  }

  public static function allLoad($limit=null) : array
  {
    $in_gallery_sql_p_w = "";
    $in_gallery_sql_p_a = "";
    if(\infosite('with_gallery')==true) {
      $sql_gallery = "SELECT GROUP_CONCAT(PP.`product`) as ids
        FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` PP
        INNER JOIN `{$GLOBALS['PACMEC']['DB']->getTableName('products')}` P
        WHERE P.is_active = 1 AND P.available > 0";
      $in_gallery = $GLOBALS['PACMEC']['DB']->FetchObject($sql_gallery, []);
      $in_gallery_sql_p_w = " WHERE `id` IN ({$in_gallery->ids}0) ";
      $in_gallery_sql_p_a = " AND `id` IN ({$in_gallery->ids}0) ";
    }
    $add_sql = " {$in_gallery_sql_p_w} ";
    $r = [];
    if($limit==null){
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` ".$add_sql;
    } else {
      $sql = "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::MODEL_TBL)}` {$add_sql} LIMIT {$limit}";
    }
    foreach($GLOBALS['PACMEC']['DB']->FetchAllObject($sql, []) as $item){
      $r[] = new Product((object) ['id'=>$item->id]);
    }
    return $r;
  }

}
