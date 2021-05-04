<?php
/**
 * Plugin Name: ERP
 * Text Domain: ERP
 * Description: Muchas empresas empresariales utilizan soluciones de recursos para administrar sus operaciones diarias, manejando datos como registros de clientes, precios de productos e inventario. Lo que no siempre está claro es cómo estos datos se pueden integrar sin problemas en su sitio web. En muchos casos, los sitios web de comercio electrónico crean y cumplen pedidos web a través de las propias herramientas de comercio electrónico. Sin embargo, esto generalmente requiere una entrada.
 *
 * Plugin URI: https://github.com/PACMEC/PACMEC-ERP
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * (email : feliphegomez@gmail.com)
 */
function pacmec_ERP_activation()
{
  try {
    require_once 'includes/definitions.php';
    require_once 'models/eMailsBoxes.php';
    require_once 'includes/shortcodes.php';
    $tbls = [
      'emails_boxes',
      'emails_users',
    ];
    foreach ($tbls as $tbl) {
      if(!pacmec_tbl_exist($tbl)){
        throw new \Exception("Falta la tbl: {$tbl}", 1);
      }
    }
    if(!isGuest()){
      $meinfo = meinfo();
      $GLOBALS['PACMEC']['session']->emails_boxes = \PACMEC\ERP\eMailsBoxes::load_users_by('user_id', $meinfo->user->id);
    }
  } catch (\Exception $e) {
    echo $e->getMessage();
    exit;
  }
}
register_activation_plugin('ERP', 'pacmec_ERP_activation');
