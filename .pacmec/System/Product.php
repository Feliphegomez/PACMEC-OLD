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
#use PHPExcel\PHPExcel_IOFactory;
class Product extends \PACMEC\System\BaseRecords
{
  const TABLE_NAME = 'products';
  const COLUMNS_AUTO_T  = [
  ];
	public $in_promo      = false;
	public $price         = 0.00;
	public $link_view     = "#";
	public $link_edit     = "#";
	public $link_remove   = "#";
	public $thumb         = "";
	public $gallery       = [];
	public $features      = [];
	public $rating_number = 0;
	public $rating_porcen = 0;

  public function __construct($opts=null)
  {
    Parent::__construct();
    if(is_object($opts) && isset($opts->id)) $this->get_by_id($opts->id);
    else if(is_object($opts) && isset($opts->ref)) $this->get_by('sku', $opts->ref);

  }

  public function set_all($obj)
  {
    Parent::set_all($obj);
    if($this->isValid()){
      $this->common_names = @explode(',', $this->common_names);
      $this->link_view = __url_S("/%products_view%/{$this->id}/".urlencode($this->name));
      $this->link_edit   = __url_S("/%admin_products_slug%?product_id={$this->id}");
      $this->link_remove = __url_S("/%admin_products_slug%?remove_product={$this->id}");
      if($this->price_normal > $this->price_promo && $this->price_promo>0){
        $this->in_promo = true;
        $this->price = $this->price_promo;
      } else {
        $this->price = $this->price_normal;
      }
      $this->gallery = [];
      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_pictures')}` WHERE `product` IN (?) ORDER BY `ordering`", [$this->id]) as $picture)
      {
        $this->gallery[] = $picture->path_short;
      }
      if(count($this->gallery)==0) $this->gallery[] = infosite('default_picture');
      $this->thumb = $this->gallery[0];

      foreach ($GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_features')}` FEA", []) as $feature) {
        $feature->items = $GLOBALS['PACMEC']['DB']->FetchAllObject("SELECT * FROM `{$GLOBALS['PACMEC']['DB']->getTableName('products_filters')}` FIL WHERE FIL.`product` IN (?) AND FIL.`feature` IN (?)", [$this->id, $feature->id]);
        $this->features[] = $feature;
      }
  		$rating = \PACMEC\System\Ratign::get_all_uri(infosite('siteurl').$this->link_view, false);
  		$this->rating_number = $rating->rating_number;
  		$this->rating_porcen = $rating->rating_porcen;
    }
  }

  public function remove_this()
  {
    $sql = "DELETE FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE  `id`=?";
    return $GLOBALS['PACMEC']['DB']->FetchObject($sql, [$this->id]);
  }

  public static function table_list_html(array $items) : String
  {
    global $PACMEC;
    $table = new \PHPStrap\Table([],0,0,['pacmec-table-all'], []);
    $table->setStylesHeader(["thead-light"]);
    $table->addHeaderRow([
      \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-plus']), ['btn btn-sm btn-outline-success btn-hover-success'], [
        'href'=>__url_s("/%admin_products_slug%?create_item=true")
      ])
      , __a('sku_ref')
      , __a('name')
      , __a('common_names')
      // , __a('description')
      , __a('unid')
      , __a('is_active')
      , __a('available')
      , __a('price_normal')
      , __a('price_promo')
      #, __a('created')
      #, __a('updated')
      , \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-database']), ['btn btn-sm btn-outline-teal btn-hover-teal'], [
        'href'=>__url_s("/%admin_products_slug%?export=true")
      ])
      . \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-cloud-upload']), ['btn btn-sm btn-outline-teal btn-hover-teal'], [
        'href'=>__url_s("/%admin_products_slug%?import=true")
      ])
    ]);
    foreach ($items as $item) {
      $tags = "";
      foreach ($item->common_names as $tag) $tags .= \PHPStrap\Util\Html::tag('span', $tag, ['pacmec-tag pacmec-small pacmec-green pacmec-round-large']);
      $btns = "";
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-eye']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-teal'], [ 'href'=>$item->link_view ]);
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-edit']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-orange'], [ 'href'=>$item->link_edit."&redirect=".urlencode(infosite('siteurl').$PACMEC['path']) ]);
      $btns .= \PHPStrap\Util\Html::tag('a', \PHPStrap\Util\Html::tag('i', '', ['fa fa-trash']), ['pacmec-button pacmec-padding-small pacmec-circle pacmec-white pacmec-border pacmec-border-red'], [ 'href'=>$item->link_remove ]);
      $table->addRow([
        // $item->id
        \PHPStrap\Util\Html::tag('img', '', [], ['src'=>$item->thumb, 'width'=>"75px"], true)
        , $item->sku
        , $item->name
          . "<br><a href=\"{$item->link_view}\" target=\"_blank\"><small>{$item->link_view}</small></a>"
        , $tags
        # , __a($item->status)
        // , $item->description
        , $item->unid
        , json_encode($item->is_active)
        , $item->available
        , formatMoney($item->price_normal)
        , formatMoney($item->price_promo)
        #, $item->created
        #, $item->updated
        , $btns
      /*
      */
      ]);
    }
    return \PHPStrap\Util\Html::tag('div', $table, ['pacmec-responsive'], []);
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
      $select = $GLOBALS['PACMEC']['DB']->FetchObject(
        "SELECT `id` FROM `{$GLOBALS['PACMEC']['DB']->getTableName(SELF::TABLE_NAME)}` WHERE ".implode(" AND ", $columns_b)
        , $items_send
      );
      if($insert!==false){
        $this->get_by_id($insert);
        return true;
      }
      return false;
  	}catch (Exception $e){
  		return false;
  	}
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

  public static function exportar2excel($items=[], $columns=[], $filename="nombredelfichero.xls", $format="Excel5")
  {
    $excel = new \PHPExcel();
    $excel->setActiveSheetIndex(0);
    $excel->getActiveSheet()->setTitle('test worksheet');

    $row    = 1;
    $column = 0;

    foreach ($columns as $column_name) {
      $excel->getActiveSheet()->setCellValue(\getNameFromNumberZero($column).$row, $column_name);
      $excel->getActiveSheet()->getStyle(\getNameFromNumberZero($column).$row)->getFont()->setBold(true);
      /*
      $excel->getActiveSheet()->getStyle("{$letter}{$column}")->getFont()->setSize(14);
      */
      $column++;
    }
    $row++;

    foreach ($items as $item) {
      $column = 0;
      foreach ($columns as $k) {
        if(isset($item->{$k})){
          if(is_array($item->{$k}) || is_object($item->{$k})){
            $excel->getActiveSheet()->setCellValue(\getNameFromNumberZero($column).$row, json_encode($item->{$k}));
          }
          else {
            $excel->getActiveSheet()->setCellValue(\getNameFromNumberZero($column).$row, $item->{$k});
          }
        }

        $column++;
      }
      $row++;
    }


    $instance = \PHPExcel_IOFactory::createWriter($excel, $format);
    if($format == "Excel2007"){ header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); }
    else { header('Content-Type: application/vnd.ms-excel'); }

    header("Expires: 0");
    #header('Cache-Control: max-age=0'); //no cache
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header('Content-Disposition: attachment;filename="'.$filename.'";');

    $instance->save('php://output');
  }

}
