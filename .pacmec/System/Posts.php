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

class Posts extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME = 'posts';
  const COLUMNS_AUTO_T  = [];
	public $link_href     = "#";
	public $thumb         = "";
	public $gallery       = [];
	public $rating_number = 0;
	public $rating_porcen = 0;
  public $post_older = null;
  public $post_newer = null;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by_id($opts->id);
  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->link_href = __url_S("/%blog_read_slug%/{$this->id}/".urlencode($this->slug));
      $this->gallery = [];
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('posts_pictures')}` WHERE `post_id` IN (?)", [$this->id]) as $picture) $this->gallery[] = $picture->path_short;
      if(count($this->gallery)==0) $this->gallery[] = infosite('default_picture');
      $this->thumb = $this->gallery[0];
  		$rating = \PACMEC\System\Ratign::get_all_uri(infosite('siteurl').$this->link_href, false);
  		$this->rating_number = $rating->rating_number;
  		$this->rating_porcen = $rating->rating_porcen;
      $tmp = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}`
      WHERE `id` < ? LIMIT 1", [$this->id]);
      if($tmp!==false) $this->post_older = $tmp;
      $tmp = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}`
      WHERE `id` > ? LIMIT 1", [$this->id]);
      if($tmp!==false) $this->post_newer = $tmp;
    }
  }
}
