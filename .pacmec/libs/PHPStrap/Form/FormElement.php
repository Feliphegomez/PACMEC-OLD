<?php
namespace PHPStrap\Form;

class FormElement implements Validable{
    
	protected $Code = "", $Attribs = array(), $Validations = array();
    
	/**
     * @return boolean
     */
    public function isValid()
    {
    	$value = $this->submitedValue();
    	if($value !== NULL){
    		foreach($this->Validations as $val){
				if(!$val->isValid($value)) return FALSE;
			}
    	}
    	return TRUE;
    }
    
	/**
     * @return string with error message (NULL if no error present)
     */
    public function errorMessage()
    {
    	$value = $this->submitedValue();
    	if($value !== NULL){
			foreach($this->Validations as $val){
				if(!$val->isValid($value)){
					return $val->errorMessage();
				}
			}
    	}
    	return NULL;
    }
    
    public function submitedValue(){
    	if($this->hasAttrib("name")){
        	if(isset($_POST[$this->getAttrib("name")])){
        		return trim($_POST[$this->getAttrib("name")]);
        	}
    	}
    	return NULL;
    }
    
    /**
     * @return string
     */
    public function __toString(){
        return $this->Code;
    }

    /**
     * @param $DefaultAttribs
     *
     * @return array
     */
    protected function setAttributeDefaults($DefaultAttribs){
        foreach($DefaultAttribs as $k=>$v){
        	if(!$this->hasAttrib($k)){
        		$this->setAttrib($k, $v);
        	}
        }
        return $this->Attribs;
    }

    /**
     * @param $Attrib
     *
     * @return bool
     */
    public function hasAttrib($Attrib){
        return isset($this->Attribs[$Attrib]) && $this->Attribs[$Attrib] != "";
    }

    /**
     * @param $Attrib
     *
     * @return mixed
     */
    public function getAttrib($Attrib){
        return $this->Attribs[$Attrib];
    }

    /**
     * @param $Attrib
     * @param $Value
     */
    protected function setAttrib($Attrib, $Value){
        $this->Attribs[$Attrib] = $Value;
    }

    /**
     * @param $Attrib
     * @param $Value
     */
    protected function addAttrib($Attrib, $Value){
        $this->Attribs[$Attrib] .= " " . $Value;
    }
    
    protected function parseAttribs($Attribs = array()){
        $Str = "";

        $Properties = array('disabled', 'readonly', 'multiple', 'checked', 'required', 'autofocus');

        foreach ($Attribs as $key => $val) {
            if (in_array($key, $Properties)) {
                if ($val === true) {
                    $Str .= ' ' . strtolower($key);
                }
            } else {
                $Str .= ' ' . $key . '="' . $val . '"';
            }
        }

        return $Str;
    }
    
    private $Help = NULL;
    
    public function withHelpText($Help){
    	$this->Help = $Help;
    }
    
    protected function helpTextSpan(){
    	return ($this->Help != NULL) ?
    		\PHPStrap\Util\Html::tag("span", $this->Help, array('help-block')) : 
    		"";
    }
    
}
