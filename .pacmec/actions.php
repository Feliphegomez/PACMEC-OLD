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

function pacmec_meta_head(){
 $a = "";
 foreach($GLOBALS['PACMEC']['website']['meta'] as $meta){
   $a .= \PHPStrap\Util\Html::tag($meta['tag'], $meta['content'], [], $meta['attrs'], (in_array($meta['tag'], ['title'])?false:true))."\t";
 }
 echo $a;
 #echo json_encode($GLOBALS['PACMEC']['website']['meta'], JSON_PRETTY_PRINT);
}
add_action('meta_head', 'pacmec_meta_head');

function pacmec_debug_box(){
 $content = \PHPStrap\Util\Html::tag('pre', json_encode($GLOBALS['PACMEC'], JSON_PRETTY_PRINT));
 return \PHPStrap\Util\Html::tag('debug', \PHPStrap\Util\Html::tag('code', $content));
}
add_shortcode('pacmec-debug', 'pacmec_debug_box');
