<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   Helper
 * @copyright  2020-2021 FelipheGomez
 * @license    license.txt
 * @version    Release: @package_version@
 * @link       http://github.com/ManagerTechnologyCO/PACMEC
 * @version    1.0.1
 */

namespace PACMEC\System;

class Helper
{
  public function url($controlador=CONTROLADOR_DEFECTO, $accion=ACCION_DEFECTO)
  {
      $urlString="index.php?controller=".$controlador."&action=".$accion;
      return $urlString;
  }
  //Helpers para las vistas
}
