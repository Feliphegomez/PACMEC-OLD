<?php
/**
 * Plugin Name: CRM PACMEC
 * Text Domain: CRM
 * Description:
 *
 * Plugin URI: https://github.com/PACMEC/PACMEC-CRM
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * (email : feliphegomez@gmail.com)
 */

function pacmec_CRM_activation()
{
 try {
   require_once 'includes/shortcodes.php';
   $tbls = [
     'orders',
     'orders_status',
   ];
   foreach ($tbls as $tbl) {
     if(!pacmec_tbl_exist($tbl)){
       throw new \Exception("Falta la tbl: {$tbl}", 1);
     }
   }
 } catch (\Exception $e) {
   echo $e->getMessage();
   exit;
 }
}
register_activation_plugin('CRM', 'pacmec_CRM_activation');
