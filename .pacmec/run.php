<?php
/**
 *
 * @package    PACMEC
 * @category   System
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('PACMEC_PATH')) define('PACMEC_PATH', __DIR__);                    // Path PACMEC
require_once PACMEC_PATH . '/.prv/settings.php';                                // configuraciones principales del sitio
require_once PACMEC_PATH . '/.prv/includes.php';                                // incluir archivos

$pacmec = \PACMEC\System\Run::exec();

//echo json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT);
