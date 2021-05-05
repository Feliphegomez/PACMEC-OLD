<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   Controllers
 * @license    license.txt
 * @version    Release: @package_version@
 * @version    1.0.1
 */
Class PacmecController extends \PACMEC\System\ControladorBase
{
  public $error    = true;
  public $details  = null;
  public $response = null;

	public function __construct()
  {
		parent::__construct();
		header('Content-Type: application/json');
	}

  private function goReturn()
  {
		echo json_encode($this);
		return json_encode($this);
  }

  public function index()
  {
		return $this->goReturn();
  }

  public function notifications_change_status_fast($data)
  {
    // $this->$data
    if(isset($data['notification_id']) && is_numeric($data['notification_id'])){
      $notification = new \PACMEC\System\Notifications((object) ['id'=>$data['notification_id']]);
      if($notification->is_read==1){
        $this->error = !($notification->unread());
      } else {
        $this->error = !($notification->read());
      }

      switch ($notification->is_read) {
        case 1:
          $this->data = "fa fa-check-circle";
          break;
        case 0:
          $this->data = "fa fa-dot-circle-o";
          break;
        default:
          $this->data = "fa fa-circle-o";
          break;
      }
    }
    return $this->goReturn();
  }
}
