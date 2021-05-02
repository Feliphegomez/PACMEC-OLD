<?php

namespace PHPStrap\Form\Validation;

class MinLengthValidation extends BaseValidation implements InputValidation{
    
	private $Minlength;
	
	public function __construct($Minlength = 1, $errormessage = "El texto es muy corto")
    {
        parent::__construct($errormessage);
        $this->Minlength = $Minlength;
    }
    
    /**
     * @param string $inputValue
     * @return boolean
     */
    public function isValid($inputValue){
    	return strlen(trim($inputValue)) > $this->Minlength;
    }
 
}
?>