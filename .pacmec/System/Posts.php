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

class Posts extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME = 'posts';
  const COLUMNS_AUTO_T  = [];
	public $link_view     = "#";
	public $link_edit     = "#";
	public $link_remove   = "#";
	public $thumb         = "";
	public $author        = null;
	public $gallery       = [];
	public $rating_number = 0;
	public $rating_porcen = 0;
  public $post_older    = null;
  public $post_newer    = null;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by_id($opts->id);
  }

  public function save($columns_save=null)
  {
  	$columns = $columns_save==null ? $this->getColumns() : $columns_save;
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
        if(isset($this->{$i}) && $i!=='id' && !empty($this->{$i}) && $this->{$i} !== null){
          $columns_f[] = $i;
          $columns_a[] = "?";
          $columns_b[] = " `{$i}`=? ";
          $items_send[] = $this->{$i};
        }
    	}
      $sql = "UPDATE `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` SET ".implode(",", $columns_b)." WHERE id=?";
      $s = array_merge($items_send, [$this->id]);
      return (bool) $GLOBALS['PACMEC']['DB']->FetchObject($sql, $s);
  	}catch (Exception $e){
  		return 0;
  	}
  }

  public function create($columns_save=null)
  {
    $columns = $this->getColumns();
  	$columns_save = $columns_save==null ? $this->getColumns() : $columns_save;
  	$columns_a = [];
  	$columns_f = [];
  	$items_send = [];
  	try {
    	foreach($columns as $i){
    		if(
          isset($this->{$i})
          && $i!=='id'
          && in_array($i, $columns_save)
        ){
    			$columns_f[] = $i;
    			$columns_a[] = "?";
    			$columns_b[] = " `{$i}`=? ";
    			$items_send[] = $this->{$i};
    		}
    	}
      $sql = "INSERT INTO `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` (".implode(',', $columns_f).")
        SELECT ".implode(",", $columns_a)."
        WHERE NOT EXISTS(SELECT 1 FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE ".implode(" AND ", $columns_b).")";
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject($sql, array_merge($items_send, $items_send));
      // sleep(1);
      $insert = $GLOBALS['PACMEC']['DB']->FetchObject(
        "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE ".implode(" AND ", $columns_b)
        , $items_send
      );
      if($insert!==false && $insert->id > 0){
        $this->get_by_id($insert->id);
        return true;
      }
      return false;
  	}catch (Exception $e){
  		return false;
  	}
  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->tags = explode(',', implode(',', explode(',', $this->tags)));
      $this->link_view   = __url_S("/%blog_read_slug%/{$this->id}/".urlencode($this->slug));
      $this->link_edit   = __url_S("/%admin_blog_slug%?article_id={$this->id}");
      $this->link_remove = __url_S("/%admin_blog_slug%?remove_article={$this->id}");
      $this->gallery = [];
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('posts_pictures')}` WHERE `post_id` IN (?)", [$this->id]) as $picture) $this->gallery[] = $picture->path_short;
      if(count($this->gallery)==0) $this->gallery[] = infosite('default_picture');
      $this->thumb = $this->gallery[0];
  		$rating = \PACMEC\System\Ratign::get_all_uri($this->link_view, false);
  		$this->rating_number = $rating->rating_number;
  		$this->rating_porcen = $rating->rating_porcen;
      $tmp = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}`
      WHERE `id` < ? LIMIT 1", [$this->id]);
      if($tmp!==false) $this->post_older = $tmp;
      $tmp = $GLOBALS['PACMEC']['DB']->FetchObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}`
      WHERE `id` > ? LIMIT 1", [$this->id]);
      if($tmp!==false) $this->post_newer = $tmp;
      $this->author = new \PACMEC\System\Users((object) ["user_id"=>$this->created_by]);
    }
  }

  public function remove_this()
  {
    $sql = "DELETE FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE  `id`=?";
    return $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->id]);
  }

  public static function table_list_html_pagination(array $items, $total_result, $page=1, $limit=25) : String
  {
    global $PACMEC;
    $max_pages_float = (float) ($total_result/$limit);
    $max_pages = (int) ($total_result/$limit);
    if($max_pages<$max_pages_float) $max_pages += 1;
    $_url_pagination = $PACMEC['fullData'];
    if(isset($_url_pagination['page'])) unset($_url_pagination['page']);
    $url_pagination = $PACMEC['path'].http_build_query($_url_pagination);
    $table = Self::table_list_html($items);
    $table .= '
      <nav>
        <ul class="pagination pagination-lg">
          <li class="page-item">
            <a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query($_url_pagination).'" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>';
          if (($page-4)>0) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-4)])).'">'.($page-4).'</a></li>';
          if (($page-3)>0) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-3)])).'">'.($page-3).'</a></li>';
          if (($page-2)>0) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-2)])).'">'.($page-2).'</a></li>';
          if (($page-1)>0) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ['page'=>($page-1)])).'">'.($page-1).'</a></li>';
          $table .= '<li class="page-item"><a class="page-link active" href="#">'.$page.'</a></li>';
          if ($max_pages>=($page+1)) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ["page"=>($page+1)])).'">'.($page+1).'</a></li>';
          if ($max_pages>=($page+2)) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ["page"=>($page+2)])).'">'.($page+2).'</a></li>';
          if ($max_pages>=($page+3)) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ["page"=>($page+3)])).'">'.($page+3).'</a></li>';
          if ($max_pages>=($page+4)) $table .= '<li class="page-item"><a class="page-link" href="'.$PACMEC['path'].'?'.http_build_query(array_merge($_url_pagination, ["page"=>($page+4)])).'">'.($page+4).'</a></li>';
    $table .= '
        </ul>
      </nav>
    </div>';
    return $table;
  }

  public static function table_list_html(array $items) : String
  {
    global $PACMEC;
    $table = new \PHPStrap\Table([],0,0,['pacmec-table-all'], []);
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-plus']), ['btn btn-sm btn-outline-success btn-hover-success'], [
        'href'=>__url_s("/%admin_blog_slug%?create_item=true")
      ])
      , __a('article')
      , __a('status')
      , __a('author')
      , __a('created')
      , __a('modified')
      , ''
    ]);

    foreach ($items as $item) {
      $tags = "";
      foreach ($item->tags as $tag) $tags .= \PHPStrap\Util\Html::tag('span', $tag, ['pacmec-tag pacmec-small pacmec-blue pacmec-round-large']);
      $btns = "";
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-eye']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-teal'], [ 'href'=>$item->link_view ]);
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-edit']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-orange'], [ 'href'=>$item->link_edit."&redirect=".urlencode(infosite('siteurl').$PACMEC['path']) ]);
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-trash']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-red'], [ 'href'=>$item->link_remove ]);
      $table->addRow([
        // $item->id
        \PHPStrap\Util\Html::tag('img', '', [], ['src'=>$item->thumb, 'width'=>"75px"], true)
        , $item->title
          . "<br><a href=\"{$item->link_view}\" target=\"_blank\"><small>{$item->link_view}</small></a>"
          . "<br>{$tags}"
        , __a($item->status)
        , $item->author
        , $item->created
        , $item->modified
        , $btns
      ]);
    }
    return \PHPStrap\Util\Html::tag('div', $table, ['pacmec-responsive'], []);
  }
}
