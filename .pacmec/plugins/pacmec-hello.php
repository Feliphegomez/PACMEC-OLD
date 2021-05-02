<?php
/**
 * Plugin Name: Hello PACMEC
 * Text Domain: pacmec-hello
 * Description: El complemento de muestra para devs PACMEC
 *
 * Plugin URI: https://managertechnology.com.co/
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez/PACMEC-Hello
 * (email : feliphegomez@gmail.com)
 */
function pacmec_Hello_PACMEC_activation()
{
 try {
   $tbls = [];
   foreach ($tbls as $tbl) {
     if(!pacmec_tbl_exist($tbl)){
       throw new \Exception("Falta la tbl: {$tbl}", 1);
     }
   }
   // echo "plugin: Hello activado";
 } catch (\Exception $e) {
   echo $e->getMessage();
   exit;
 }
}
\register_activation_plugin('pacmec-hello', 'pacmec_Hello_PACMEC_activation');
