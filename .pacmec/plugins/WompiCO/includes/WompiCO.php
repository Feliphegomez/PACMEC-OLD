<?php
/**
 *
 * @package    PACMEC
 * @category   WompiCO
 * @copyright  2020-2021 Manager Technology CO & FelipheGomez CO
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @license    license.txt
 * @version    0.0.1
 */
namespace WompiCO;

class WompiCO
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
  private $link                = null;
  public  $name                = 'WompiCO';
  public  $form                = null;
  // public  $payment_methods     = [];
  // public  $acceptance_token    = null;
  // public  $permalink           = null;

  public function __construct($mode=null)
  {
    $mode = empty($mode) ? infosite('wompi_mode') : $mode;
    if ($mode == 'sandbox') {
      $this->uri        = SELF::SANDBOX_URL."/".SELF::VERSION_API;
      $this->key_pub    = "pub_test_"    . infosite('wompi_pub_test');
      $this->key_priv   = "prv_test_"    . infosite('wompi_prv_test');
      $this->key_events = "test_events_" . infosite('wompi_test_events');
    } elseif ($mode == 'production') {
      $this->uri        = SELF::PRODUCTION_URL."/".SELF::VERSION_API;
      $this->key_pub    = "pub_prod_"    . infosite('wompi_pub_prod');
      $this->key_priv   = "prv_prod_"    . infosite('wompi_prv_prod');
      $this->key_events = "prod_events_" . infosite('wompi_prod_events');
    }
    $this->load_merchant();
  }

  public function get_name()
  {
    return $this->name;
  }

  /*
  * partial / full
  */
  public function get_url_checkout($amount_in_cents, $reference, $redirect_url=null)
  {
    $redirect_url = $redirect_url==null ? full_url($_SERVER) : $redirect_url;
    return SELF::CHECKOUT_URL
      . "?public-key=" . urlencode($this->key_pub)
      . "&amp;currency=" . urlencode(infosite('site_currency'))
      . "&amp;amount-in-cents=" . urlencode($amount_in_cents)
      . "&amp;reference=" . urlencode(\encrypt($reference.":".date("YmdHis"), \infosite('pim_hash_tx')))
      . "&amp;redirect-url=" . urlencode($redirect_url);
  }

  /*
  * partial / full
  */
  public function get_form_pay_amount($reference, $redirect_url=null)
  {
    global $PACMEC;
    $redirect_url = $redirect_url==null ? full_url($_SERVER) : $redirect_url;
    $form = new \PHPStrap\Form\Form(
    '' // SELF::CHECKOUT_URL
    , "POST"
    , \PHPStrap\Form\FormType::Horizontal
    , 'Error:'
    , "Success"
    , ['class'=>'form   ']);
    # OBLIGATORIOS
    $form->addFieldWithLabel(
      \PHPStrap\Form\Number::withNameAndValue('amount-in-cents', '', 1500, [
        new \PHPStrap\Form\Validation\RequiredValidation(__a('required_field'))
        //, new \PHPStrap\Form\Validation\MinLengthValidation(1500)
        , new \PHPStrap\Form\Validation\LambdaValidation('Ingrese un monto superior a $1500 COP', function () use ($PACMEC, $redirect_url, $form) {
          if(isset($PACMEC['fullData']["amount-in-cents"])){
            if($PACMEC['fullData']["amount-in-cents"]>=1500){
              //$PACMEC['fullData']["amount-in-cents"] = (float) $PACMEC['fullData']["amount-in-cents"] * 100;
              return true;
            }
          }
          return false;
        })
      ], [
        'autocomplete'=>'off'
      ])
      , ""
      , ''
      , ['col-lg-12']
    );
    $form->Code .= "<br>";
    $form->addSubmitButton(__a('order_pay_btn'), [
      'name'=>"submit-pay-full-wompico",
      "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
    ]);
    if($form->isValid()){
      $url_send = SELF::CHECKOUT_URL
        . "?public-key=" . urlencode($this->key_pub)
        . "&amp;currency=" . urlencode(infosite('site_currency'))
        . "&amp;amount-in-cents=" . urlencode((float) $PACMEC['fullData']["amount-in-cents"] * 100)
        . "&amp;reference=" . urlencode(\encrypt($reference.":".date("YmdHis"), \infosite('pim_hash_tx')))
        . "&amp;redirect-url=" . urlencode($redirect_url);
      // echo $url_send;
      #return $url_send;
      #exit;
      echo "<meta http-equiv=\"refresh\" content=\"0;URL='{$url_send}'\" />";
    }

    return $form;
  }

  /*
  * partial / full
  */
  public function get_btn_pay($amount_in_cents, $reference, $redirect_url=null)
  {
    $redirect_url = $redirect_url==null ? full_url($_SERVER) : $redirect_url;
    $form = new \PHPStrap\Form\Form(
      SELF::CHECKOUT_URL
      , SELF::CHECKOUT_METHOD
      , \PHPStrap\Form\FormType::Horizontal
      , 'Error:'
      , "Success"
      , ['class'=>'form row']);
      # OBLIGATORIOS
      $form->hidden([
        [
          'name'   => 'public-key',
          'value'  => $this->key_pub
        ],
        [
          'name'   => 'currency',
          'value'  => infosite('site_currency')
        ],
        [
          'name'   => 'amount-in-cents',
          'value'  => $amount_in_cents
        ],
        [
          'name'   => 'reference',
          'value'  => \encrypt($reference.":".date("YmdHis"), "WompiCO")
        ],
        [
          'name'   => 'redirect-url',
          'value'  => $redirect_url
        ]
      ]);

    $form->addSubmitButton(__a('checkout_btn'), [
      'name'=>"submit-pay-full-wompico",
      "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
    ]);

    return $form;
  }

  /*
  * partial / full
  */
  public function get_form_html_full_pay($amount_in_cents, $reference, $redirect_url=null)
  {
    $redirect_url = $redirect_url==null ? full_url($_SERVER) : $redirect_url;
    $form = new \PHPStrap\Form\Form(
      SELF::CHECKOUT_URL
      , SELF::CHECKOUT_METHOD
      , \PHPStrap\Form\FormType::Horizontal
      , 'Error:'
      , "Success"
      , ['class'=>'form row']);
      # OBLIGATORIOS
      $form->hidden([
        [
          'name'   => 'public-key',
          'value'  => $this->key_pub
        ],
        [
          'name'   => 'currency',
          'value'  => infosite('site_currency')
        ],
        [
          'name'   => 'amount-in-cents',
          'value'  => $amount_in_cents
        ],
        [
          'name'   => 'reference',
          'value'  => \encrypt($reference.":".date("YmdHis"), "WompiCO")
        ],
        [
          'name'   => 'redirect-url',
          'value'  => $redirect_url
        ]
      ]);

    $form->addSubmitButton(__a('checkout_btn'), [
      'name'=>"submit-pay-full-wompico",
      "class" => 'btn btn-sm btn-outline-dark btn-hover-primary'
    ]);

    return $form;
  }

  public function load_merchant()
  {
  }

  public function ___load_merchant()
  {
    // $this->link
    global $PACMEC;
    $PaymentGateways = $PACMEC['gateways']['payments'];
    $this->link = curl_init();
    curl_setopt($this->link, CURLOPT_URL, "{$this->uri}/merchants/{$this->key_pub}");
    curl_setopt($this->link, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->link, CURLOPT_HEADER, 0);
    curl_setopt($this->link, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    $data = curl_exec($this->link);
    curl_close($this->link);
    $data = json_decode($data);
    if(isset($data->data) && is_object($data->data)){
      $InData = $data->data;
      if($InData->active){
        $this->payment_methods = $InData->accepted_payment_methods;
        $this->acceptance_token = $InData->presigned_acceptance->acceptance_token;
        $this->permalink = $InData->presigned_acceptance->permalink;
        $PaymentGateways->add_provider($this);
      }
    }
    //$WompiCO = (object) [];
    //$WompiCO->name = "Wompi";
    //$PaymentGateways->add_provider('wompi');

  }

  public static function active($value='')
  {
    global $PACMEC;
    $PaymentGateways = $PACMEC['gateways']['payments'];
    $PaymentGateways->add_provider(new Self);
  }

  public function test_merchant()
  {
    echo "Test carga WompiCO. \n";
    echo "URI: {$this->uri} \n";
    // $fp = fopen("/merchants/{$this->key_pub}", "w");
    $this->link = curl_init();
    curl_setopt($this->link, CURLOPT_URL, "{$this->uri}/merchants/{$this->key_pub}");
    curl_setopt($this->link, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->link, CURLOPT_HEADER, 1);
    curl_setopt($this->link, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    $data = curl_exec($this->link);
    curl_close($this->link);
    $data = json_decode($data);
    echo json_encode($data, JSON_PRETTY_PRINT);
  }
}
