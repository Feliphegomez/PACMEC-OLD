<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   Debug
 * @copyright  2020-2021 Manager Technology CO
 * @license    license.txt
 * @version    Release: @package_version@
 * @link       http://github.com/ManagerTechnologyCO/PACMEC
 * @version    1.0.1
 */

#echo "FOOTER DEBUG:                 " . "---------------<hr>\n";
#echo "PACMEC_PATH:                  " . PACMEC_PATH . "\n";
#echo "SITE_PATH:                    " . SITE_PATH   . "\n";
#echo "\$GLOBALS['PACMEC'] JSON:     " . json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT)   . "\n";

#echo json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT);

#  json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT)

$content = PHPStrap\Util\Html::tag('pre', json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT));
echo PHPStrap\Util\Html::tag('debug', PHPStrap\Util\Html::tag('code', $content));
