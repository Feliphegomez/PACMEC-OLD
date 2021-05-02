<?php
namespace PHPStrap;

class Column{

	private $Content, $Width;
	
	public function __construct($Content = "", $Width = NULL){
        $this->Content = $Content;
        $this->Width = $Width;
    }
    
	public function getContent(){
    	return $this->Content;
    }
    
	public function getWidth(){
    	return $this->Width;
    }
	
}
