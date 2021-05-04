<?php
/**
 * Plugin Name: CMS PACMEC
 * Text Domain: CMS
 * Description:
 *
 * Plugin URI: https://github.com/PACMEC/PACMEC-CMS
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * (email : feliphegomez@gmail.com)
 */

function pacmec_CMS_activation()
{
 try {
   require_once 'includes/shortcodes.php';
   $tbls = [
     'posts',
     'posts_pictures',
     // 'posts_tags',
     // 'tags',
   ];
   foreach ($tbls as $tbl) {
     if(!pacmec_tbl_exist($tbl)){
       throw new \Exception("Falta la tbl: {$tbl}", 1);
     }
   }
   //require_once PACMEC_PATH."/plugins/PIM/models/Filters.php";

   \add_action('route_extends_path', function(){
     // Explorar Path URI en caso de tener contenido de productos
     $_explo = explode('/', $GLOBALS['PACMEC']['path']);
     $_exploder = [];
     foreach ($_explo as $key => $value) { if(!empty($value)) $_exploder[] = $value; }
     // 3 atributos para el producto
     if(
       isset($_exploder[2]) && count($_exploder)==3 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%blog_read_slug%']
       || isset($_exploder[2]) && count($_exploder)==3 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%blog_embeded%']
     ) {
       if(is_numeric($_exploder[1])){
         $search = new \PACMEC\System\Posts((object) ['id'=>$_exploder[1]]);
         if($search->isValid()){
           $GLOBALS['PACMEC']['route']->id = $_exploder[1];
           $GLOBALS['PACMEC']['route']->request_uri = $GLOBALS['PACMEC']['path'];
           $GLOBALS['PACMEC']['route']->title = __a('blog_read') . ' ' . $search->title;
           $GLOBALS['PACMEC']['route']->description = strip_tags($search->content);
           $GLOBALS['PACMEC']['route']->content = strip_tags($search->content);
           $GLOBALS['PACMEC']['route']->keywords = implode(',', explode(',', $search->tags)).','.infosite('sitekeywords');;
           $GLOBALS['PACMEC']['route']->layout = 'pages-blog-details';
           $GLOBALS['PACMEC']['route']->post = $search;
           $GLOBALS['PACMEC']['route']->comments_enabled = infosite('comments_enabled');
           if($_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%blog_embeded%']) {
             $GLOBALS['PACMEC']['route']->is_embeded = true;
             $GLOBALS['PACMEC']['route']->layout = 'pages-blog-embeded';
           }
           pacmec_add_meta_tag('image', $search->thumb);
           pacmec_add_meta_tag('og:type', 'og:article');
           pacmec_add_meta_tag('og:modified_time', $search->modified);
        }
      }
    }
     // 1 Atributo para explorador de la tienda
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%blog_slug%']) {
       $GLOBALS['PACMEC']['route']->id = 1;
       $GLOBALS['PACMEC']['route']->title = __a('blog');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr');
       $GLOBALS['PACMEC']['route']->layout = 'pages-blog';
       $GLOBALS['PACMEC']['route']->content = '';
     }

     #echo json_encode($GLOBALS['PACMEC']['route']);
     #exit;
   });
 } catch (\Exception $e) {
   echo $e->getMessage();
   exit;
 }
}
register_activation_plugin('CMS', 'pacmec_CMS_activation');
