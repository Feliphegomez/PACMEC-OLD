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
namespace PACMEC\System;

class Product extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME = 'products';
  const COLUMNS_AUTO_T  = [
    "unid" => [
      "s"      => "_",
      "autoT"  => true,
      "parts"  => [
        "unids",
        "unid",
      ]
    ]
  ];
	public $in_promo      = false;
	public $price         = 0.00;
	public $link_href     = "#";
	public $thumb         = "";
	public $gallery       = [];
	public $features      = [];
	public $rating_number = 0;
	public $rating_porcen = 0;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(
      is_object($opts)
      && isset($opts->id)
    ) {
      $this->get_by_id($opts->id);
    }
  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->link_href = __url_S("/%products_view%/{$this->id}/".urlencode($this->name));
      if($this->price_normal > $this->price_promo && $this->price_promo>0){
        $this->in_promo = true;
        $this->price = $this->price_promo;
      } else {
        $this->price = $this->price_normal;
      }
      /*
      if($this->rating>0){
        $this->rating_number = (int) ($this->rating);
        $this->rating_porcen = (int) (($this->rating*100)/5);
      }
      */
      $this->gallery = [];
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` WHERE `product` IN (?)", [$this->id]) as $picture)
      {
        $this->gallery[] = $picture->path_short;
      }
      if(count($this->gallery)==0) $this->gallery[] = infosite('default_picture');
      $this->thumb = $this->gallery[0];

      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_features')}` FEA", []) as $feature) {
        $feature->items = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` FIL WHERE FIL.`product` IN (?) AND FIL.`feature` IN (?)", [$this->id, $feature->id]);
        $this->features[] = $feature;
      }
  		$rating = \PACMEC\System\Ratign::get_all_uri(infosite('siteurl').$this->link_href, false);
  		$this->rating_number = $rating->rating_number;
  		$this->rating_porcen = $rating->rating_porcen;
    }
  }
}
