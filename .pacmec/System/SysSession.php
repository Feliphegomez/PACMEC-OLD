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

namespace PACMEC\System;

class SysSession implements \SessionHandlerInterface {
  const IPS_BANNED = [
    '66.249.64.83'
  ];
  private $link;

  public function open($savePath, $sessionName) {
    $link = $GLOBALS['PACMEC']['DB'];
    if($link){
      $this->link = $link;
      return true;
    } else { return false; }
  }

  public function close() {
    $this->link = NULL;
    return true;
  }

  public function read($id) {
  	try {
  		$result = $this->link->FetchObject("SELECT `session_data` FROM `{$GLOBALS['PACMEC']['DB']->getTableName('sessions')}` WHERE `session_id`=? AND `session_expires`>=? AND `ip`=? AND `host`=?", [
        $id, date('Y-m-d H:i:s'),
        $GLOBALS['PACMEC']['ip'],
        $GLOBALS['PACMEC']['host']
      ]);
  		if($result !== false && isset($result->session_data)){ return $result->session_data; } else { return ""; }
  	}
  	catch(Exception $e){
  		echo $e->getMessage();
  		return "";
  	}
  }

  public function write($id, $data) {
  	try {
  		$DateTime = date('Y-m-d H:i:s');
  		$NewDateTime = date('Y-m-d H:i:s',strtotime($DateTime.' + 1 hour'));
  		$result = $this->link->FetchObject("REPLACE INTO `{$GLOBALS['PACMEC']['DB']->getTableName('sessions')}` SET `session_id`=?, `session_expires`=?, `session_data`=?, `ip`=?, `host`=?", [
        $id,
        $NewDateTime,
        $data,
        $GLOBALS['PACMEC']['ip'],
        $GLOBALS['PACMEC']['host']
      ]);
  		if($result !== false){ return true; } else { return false; }
  	}
  	catch(Exception $e){
  		echo $e->getMessage();
  		return false;
  	}
  }

  public function destroy($id) {
  	try {
  		$result = $this->link->FetchObject("DELETE FROM `{$GLOBALS['PACMEC']['DB']->getTableName('sessions')}` WHERE `session_id`=?", [$id]);
  		if($result !== false){ return true; } else { return false; }
  	}
  	catch(Exception $e){
  		echo $e->getMessage();
  		return false;
  	}
  }

  public function gc($maxlifetime) {
  	try {
  		$result = $this->link->FetchObject("DELETE FROM `{$GLOBALS['PACMEC']['DB']->getTableName('sessions')}` WHERE ((UNIX_TIMESTAMP(session_id)+?)<?)", [$maxlifetime, $maxlifetime]);
  		if($result !== false){ return true; } else { return false; }
  	}
  	catch(Exception $e){
  		echo $e->getMessage();
  		return false;
  	}
  }
}
