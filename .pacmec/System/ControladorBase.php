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

class ControladorBase {
	private $PACMEC;

  public function __construct()
	{
		global $PACMEC;
		$this->set('PACMEC', $PACMEC);
  }

	public function set($k, $v)
	{
		$this->{$k} = $v;
	}

  public function view($vista,$datos)
	{
    foreach ($datos as $id_assoc => $valor) {
      ${$id_assoc}=$valor;
    }
    require_once CORE_PATH . '/AyudaVistas.php';
    $helper=new AyudaVistas();
    require_once CORE_PATH . '/view/'.$vista.'View.php';
  }

  public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
    header("Location: index.php?controller=".$controlador."&action=".$accion);
  }
}
?>
