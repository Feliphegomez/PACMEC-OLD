<?php
/**
 * Plugin Name: PIM PACMEC
 * Text Domain: PIM
 * Description: Si bien la solución ERP de una empresa puede contener datos esenciales para administrar las operaciones de su empresa, es posible que no tenga información detallada sobre sus productos.
 *
 * Plugin URI: https://github.com/PACMEC/PACMEC-PIM
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * (email : feliphegomez@gmail.com)
 */

function pacmec_PIM_activation()
{
 try {
   define('GALLERIES_SHOP_PATH', dirname(PACMEC_PATH) . "/public/galleries/shop");
   require_once 'includes/shortcodes.php';
   $tbls = [
     'products',
     'products_pictures',
     'products_features',
     'products_features_list',
     'products_filters',
     'orders',
     'orders_items',
     'orders_status',
     'orders_tx',
     'users_orders',
     'coupon_codes',
     'payments',
     'shoppings_carts',
   ];
   foreach ($tbls as $tbl) {
     if(!pacmec_tbl_exist($tbl)){
       throw new \Exception("Falta la tbl: {$tbl}", 1);
     }
   }
   require_once PACMEC_PATH."/plugins/PIM/models/Filters.php";

   \add_action('route_extends_path', function(){
     // Explorar Path URI en caso de tener contenido de productos
     $_explo = explode('/', $GLOBALS['PACMEC']['path']);
     $_exploder = [];
     foreach ($_explo as $key => $value) { if(!empty($value)) $_exploder[] = $value; }
     // 3 atributos para el producto
     if(
       isset($_exploder[2]) && count($_exploder)==3 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%products_view%']
       || isset($_exploder[2]) && count($_exploder)==3 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%products_embeded%']
     ) {
       if(is_numeric($_exploder[1])){
         $search_product = new \PACMEC\System\Product((object) ['id'=>$_exploder[1]]);
         if($search_product->isValid()){
           $GLOBALS['PACMEC']['route']->id = $_exploder[1];
           $GLOBALS['PACMEC']['route']->request_uri = $GLOBALS['PACMEC']['path'];
           $GLOBALS['PACMEC']['route']->title = __a('products_view') . ' ' .$search_product->name . ' | ' . __a('sku_ref') . ': ' . $search_product->sku;
           $GLOBALS['PACMEC']['route']->description = $search_product->description;
           $GLOBALS['PACMEC']['route']->content = strip_tags($search_product->description);
           $GLOBALS['PACMEC']['route']->keywords = implode(',', $search_product->common_names).','.infosite('sitekeywords');;
           $GLOBALS['PACMEC']['route']->layout = 'pages-product-view';
           $GLOBALS['PACMEC']['route']->product = $search_product;
           $GLOBALS['PACMEC']['route']->comments_enabled = infosite('comments_enabled');
           if($_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%products_embeded%']) {
             $GLOBALS['PACMEC']['route']->is_embeded = true;
             $GLOBALS['PACMEC']['route']->layout = 'pages-product-embeded';
           }
           pacmec_add_meta_tag('image', $search_product->thumb);
           pacmec_add_meta_tag('og:type', 'og:product');
           pacmec_add_meta_tag('product:plural_title', $search_product->name);
           pacmec_add_meta_tag('ia:markup_url', infosite('siteurl').$GLOBALS['PACMEC']['path']);
           $price = $search_product->in_promo==true?$search_product->price_promo:$search_product->price;
           pacmec_add_meta_tag('product:price:amount', $price);
           pacmec_add_meta_tag('product:price:currency', infosite('site_currency'));
         }
       }
     }
     // 1 Atributo para explorador de la tienda
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%shop_slug%']) {
       $GLOBALS['PACMEC']['route']->id = 1;
       $GLOBALS['PACMEC']['route']->title = __a('shop_title');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr');
       $GLOBALS['PACMEC']['route']->layout = 'pages-products';
     }
     // 1 Atributo para ver una orden por ref
     elseif(
       isset($_exploder[1]) && count($_exploder)==2 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%order_view%']
       || isset($_exploder[1]) && count($_exploder)==2 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%order_view_embeded_slug%']
     ) {
       if(is_numeric($_exploder[1])){
         $order = new \PACMEC\System\Orders((object) ['order_id' => $_exploder[1]]);
       } else {
         $order = new \PACMEC\System\Orders((object) ['order_ref' => $_exploder[1]]);
       }
       if($order->isValid()){
         $GLOBALS['PACMEC']['route']->id = $order->id;
         $GLOBALS['PACMEC']['route']->title = __a('order_view') . $order;
         $GLOBALS['PACMEC']['route']->description = infosite('sitedescr') . $order;
         $GLOBALS['PACMEC']['route']->layout = 'pages-order-view';
         $GLOBALS['PACMEC']['route']->order = $order;
         if($_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%order_view_embeded_slug%']) {
           $GLOBALS['PACMEC']['route']->is_embeded = true;
           $GLOBALS['PACMEC']['route']->layout = 'pages-order-view-embeded';
         }
       }
     }
     // 1 Atributo para el carrito
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%cart_slug%']) {
       $GLOBALS['PACMEC']['route']->id = session_id();
       $GLOBALS['PACMEC']['route']->title = __a('shopping_cart');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr') . __a('shopping_cart');
       $GLOBALS['PACMEC']['route']->layout = 'pages-scart';
       $GLOBALS['PACMEC']['route']->shopping_cart = $GLOBALS['PACMEC']['session']->shopping_cart;
     }
     // 1 Atributo para el carrito
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%checkout%']) {
       $GLOBALS['PACMEC']['route']->id = session_id();
       $GLOBALS['PACMEC']['route']->title = __a('checkout_title');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr') . __a('checkout_title');
       $GLOBALS['PACMEC']['route']->layout = 'pages-checkout';
       $GLOBALS['PACMEC']['route']->shopping_cart = $GLOBALS['PACMEC']['session']->shopping_cart;
     }
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%admin_products_slug%']) {
       $GLOBALS['PACMEC']['route']->id = 1;
       $GLOBALS['PACMEC']['route']->permission_access = 'products:admin';
       $GLOBALS['PACMEC']['route']->title = __a('manage') . " " . __a('products');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr');
       $GLOBALS['PACMEC']['route']->layout = 'pages-none';
       $GLOBALS['PACMEC']['route']->content = '[pacmec-admin-products-table][/pacmec-admin-products-table]';
     }
     else if(isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === $GLOBALS['PACMEC']['permanents_links']['%admin_galleries_shop_slug%']) {
       $GLOBALS['PACMEC']['route']->id = 1;
       $GLOBALS['PACMEC']['route']->permission_access = 'products:admin';
       $GLOBALS['PACMEC']['route']->title = __a('manage') . " " . __a('products');
       $GLOBALS['PACMEC']['route']->description = infosite('sitedescr');
       $GLOBALS['PACMEC']['route']->layout = 'pages-none';
       $GLOBALS['PACMEC']['route']->content = '[pacmec-admin-galleries-shop-table][/pacmec-admin-galleries-shop-table]';
     }

     #echo json_encode($GLOBALS['PACMEC']['route']);
     #exit;
   });
 } catch (\Exception $e) {
   echo $e->getMessage();
   exit;
 }
}
register_activation_plugin('PIM', 'pacmec_PIM_activation');
