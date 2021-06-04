<?php
/**
 * Plugin Name: AMS
 * Plugin URI: https://managertechnology.com.co/
 * Description: El complemento de PACMEC para gestion AMS
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez/PACMEC-AMS
 * Text Domain: AMS
 * (email : feliphegomez@gmail.com)
 * GPLv2 Full license details in license.txt
 */

function pacmec_AMS_activation()
{
 try {
   require_once 'models/Memberships.php';
   require_once 'includes/shortcodes.php';
   $tbls = [];
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
register_activation_plugin('AMS', 'pacmec_AMS_activation');
