<?php
/**
 * Plugin Name: Wompi Colombia PACMEC
 * Text Domain: WompiCO
 * Description: Plugin oficial para pagos usando la pasarela de Bancolombia
 *
 * Plugin URI: https://github.com/PACMEC/PACMEC-WompiCO
 * Version: 0.1
 * Author: FelipheGomez
 * Author URI: https://github.com/FelipheGomez
 * (email : feliphegomez@gmail.com)
 */

function pacmec_WompiCO_activation()
{
 try {
   require_once PACMEC_PATH."/plugins/WompiCO/models/WompiStatus.php";
   require_once PACMEC_PATH."/plugins/WompiCO/models/WompiSyncHistory.php";
   require_once PACMEC_PATH."/plugins/WompiCO/includes/WompiCO.php";
   require_once PACMEC_PATH."/plugins/WompiCO/includes/WompiCOEvents.php";
   global $PACMEC;
   $PaymentGateways = $PACMEC['gateways']['payments'];

   // require_once 'includes/shortcodes.php';
   $tbls = [
     'payments',
     'orders_tx',
   ];
   foreach ($tbls as $tbl) {
     if(!pacmec_tbl_exist($tbl)){
       throw new \Exception("Falta la tbl: {$tbl}", 1);
     }
   }
   $options_ckecks = [
     'wompi_pub_test'            => false,
     'wompi_prv_test'            => false,
     'wompi_test_events'         => false,
     'wompi_pub_prod'            => false,
     'wompi_prv_prod'            => false,
     'wompi_prod_events'         => false,
     'wompi_mode'                => false,
     'wompi_integration_method'  => false,
   ];
   foreach ($options_ckecks as $key => $value) { if(\infosite($key) !== 'NaN' && !empty($key)) $options_ckecks[$key] = true; }
   if(in_array(false, array_values($options_ckecks)) == true) throw new \Exception("Error en las opciones del sitio.".\json_encode($options_ckecks, JSON_PRETTY_PRINT)."\n", 1);
   global $WompiCO;
   $WompiCO = \WompiCO\WompiCO::active();

   // Explorar Path URI en caso de tener contenido de productos
   $_explo = explode('/', $GLOBALS['PACMEC']['path']);
   $_exploder = [];
   foreach ($_explo as $key => $value) { if(!empty($value)) $_exploder[] = $value; }
   if(
     isset($_exploder[0]) && count($_exploder)==1 && $_exploder[0] === 'WompiCOEvents'
   ) {
     //sleep(5);
     // $environment = isset($data['environment']) ? $data['environment'] : null;
		 header('Content-Type: application/json');
     $WompiCOEvents = new \WompiCO\WompiCOEvents();

     echo json_encode($WompiCOEvents);
     exit;
   }
 } catch (\Exception $e) {
   echo $e->getMessage();
   exit;
 }
}
register_activation_plugin('WompiCO', 'pacmec_WompiCO_activation');
