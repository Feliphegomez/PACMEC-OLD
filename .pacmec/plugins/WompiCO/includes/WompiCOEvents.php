<?php
/**
 *
 * @package    PACMEC
 * @category   WompiCO
 * @copyright  2020-2021 FelipheGomez & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace WompiCO;

class WompiCOEvents
{
  const VERSION_API            = 'v1';
  const PROTOCOL_URL           = "https";
  const CHECKOUT_URL           = "https://checkout.wompi.co/p/";
  const CHECKOUT_METHOD        = "GET";
  const SANDBOX_URL            = "https://sandbox.wompi.co";
  const PRODUCTION_URL         = "https://production.wompi.co";
  private $uri                 = "";
  private $key_pub             = "";
  private $key_priv            = "";
  private $key_events          = "";

	public $error                = true;
	public $message              = "";
	public $error_details        = null;

  public function __construct()
  {
    global $PACMEC;
    $resultado_json = null;
    $mode = empty($mode) ? infosite('wompi_mode') : $mode;
    if($mode == 'sandbox') {
      $this->uri        = SELF::SANDBOX_URL."/".SELF::VERSION_API;
      $this->key_pub    = "pub_test_"    . infosite('wompi_pub_test');
      $this->key_priv   = "prv_test_"    . infosite('wompi_prv_test');
      $this->key_events = "test_events_" . infosite('wompi_test_events');
    }
    elseif($mode == 'production') {
      $this->uri        = SELF::PRODUCTION_URL."/".SELF::VERSION_API;
      $this->key_pub    = "pub_prod_"    . infosite('wompi_pub_prod');
      $this->key_priv   = "prv_prod_"    . infosite('wompi_prv_prod');
      $this->key_events = "prod_events_" . infosite('wompi_prod_events');
    }

    $valid = "";
    $InData = $PACMEC['fullData'];

		$environment = isset($InData['environment'])           ? $InData['environment']           : null;
		$checksum    = isset($InData['signature']->checksum)   ? $InData['signature']->checksum   : null;
		$properties  = isset($InData['signature']->properties) ? $InData['signature']->properties : null;
		$event       = isset($InData['event'])                 ? $InData['event']                 : null;
		$datas        = isset($InData['data'])                  ? $InData['data']                  : null;
		$timestamp   = isset($InData['timestamp'])             ? $InData['timestamp']             : null;
		$sent_at     = isset($InData['sent_at'])               ? $InData['sent_at']               : 'no_sent_at';

  	if($event !== null && $environment !== null && !empty($datas)){
			$model = new \WompiCO\WompiSyncHistory();
      $model->environment = $environment;
			$model->event = $event;
			$model->data = $datas;
			$model->sent_at = $sent_at;
			$model->ip = \getIpRemote();
			$model->checksum = $checksum;
			foreach ($properties as $i=>$key) {
				$abc = explode('.', $key);
				$valid .= $datas->{$abc[0]}->{$abc[1]};
			}
			$valid = hash("sha256", $valid.$timestamp.$this->key_events);
			if($valid == $checksum){
				switch ($model->event) {
          case 'nequi_token.updated':
            break;
          case 'transaction.updated':
            if(isset($datas->transaction->status)){
              $new_status = new \WompiCO\WompiStatus((object) ['status_wompi' => $datas->transaction->status]);
              if($new_status->isValid()){
                $ref = explode(':', \decrypt($datas->transaction->reference, \infosite('pim_hash_tx')) );
                $model->transaction_id = $datas->transaction->id;
                $model->amount_in_cents = $datas->transaction->amount_in_cents;
                $model->reference = $datas->transaction->reference;
                $model->currency = $datas->transaction->currency;
                $model->status = $datas->transaction->status;
                $model->payment_method_type = $datas->transaction->payment_method_type;
                // decode reference
                // foreach ($ref as $i => $value) { $ref[$i] = base64_decode($value); }
                if(isset($ref[0]) && isset($ref[1])){
                  switch ($ref[0]) {
                    case 'order_id':

                    $order = new \PACMEC\System\Orders((object) ['order_id'=>$ref[1]]);
                    // $pay_enabled->enabled
                    // $order->payment_total
                    // $order->total - $order->payment_total
                    if($order->isValid()){
                      $change_status = $order->change_status($new_status->id);
                      // echo json_encode($order);
                      $exist = false;
                      foreach ($order->payments as $payment) {
                        if($payment->tx->transaction_id == $datas->transaction->id){
                          $exist = true;
                          break;
                        }
                      }
                      if($exist == false){
                        $payment = new \PACMEC\System\Payments();
                        $payment->order_id           = $order->id;
                        $payment->ref                = $datas->transaction->reference;
                        $payment->provider           = "WompiCO";
                        $payment->environment        = $environment;
                        $payment->method_pay         = $datas->transaction->payment_method_type;
                        $payment->currency           = $datas->transaction->currency;
                        $payment->amount_in_cents    = $datas->transaction->amount_in_cents;
                        $payment->amount_payment     = $datas->transaction->status=='APPROVED'?($datas->transaction->amount_in_cents/100):0;
                        $payment->status             = $datas->transaction->status;
                        $payment->transaction_id     = $datas->transaction->id;
                        $payment->sent_at            = $sent_at;
                        $payment->data               = $datas;
                        $payment->ip                 = \getIpRemote();
                        $ins = $payment->create();
                        if($ins!==false&&$ins>0)
                        {
                          $resultado_json = $payment;
                          $orderTx = new \PACMEC\System\OrdersTx();
                          $orderTx->order_id = $order->id;
                          $orderTx->tx = $ins;
                          $insTx = $orderTx->create();
                        }
                      }
                    }
                    //echo json_encode($resultado_json);
                    break;
                    default:
                    break;
                  }
                }
              }
  					}
            break;
          default:
            break;
        }
      } else {
				$this->setError("Intenta nuevamente y seras reportado por tu conducta.");
			}
    }
    $model->session = json_encode($PACMEC['session']);
    $PACMEC['session']->close();
    $model->create();
    if($resultado_json!==null) echo json_encode($resultado_json);
    else json_encode($model);
    exit;
  }

	public function setError($message){
		$this->error = true;
		$this->message = $message;
	}

	public function setSuccess($message){
		$this->error = false;
		$this->message = $message;
	}

}
