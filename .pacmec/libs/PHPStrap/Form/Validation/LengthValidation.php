<?php

namespace PHPStrap\Form\Validation;

class LengthValidation extends BaseValidation implements InputValidation{
    
	private $Maxlength;
	
	public function __construct($Maxlength = 255, $errormessage = "El texto excede la longitud máxima")
    {
        parent::__construct($errormessage);
        $this->Maxlength = $Maxlength;
    }
    
    /**
     * @param string $inputValue
     * @return boolean
     */
    public function isValid($inputValue){
    	return strlen(trim($inputValue)) <= $this->Maxlength;
    }
 
}
?>