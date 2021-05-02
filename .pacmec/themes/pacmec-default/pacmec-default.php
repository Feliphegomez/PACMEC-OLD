<?php
/**
 * Theme Name: Default PACMEC
 * Theme URI: https://github.com/PACMEC/Theme-Default
 * Description: El tema de muestra para devs PACMEC
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * Text Domain: pacmec-default
 * Copyright 2020-2021
 * (email : feliphegomez@gmail.com)
 * GPLv2 Full license details in license.txt
 */
function pacmec_Theme_PACMEC_Default_activation()
{
  try {
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
\register_activation_plugin('pacmec-default', 'pacmec_Theme_PACMEC_Default_activation');
