<?php
namespace PACMEC\Form;

class Number extends GeneralInput  implements Validable
{
	public function __construct($Attribs = array(), $Validations = array())
	{
  	$this->Attribs = $Attribs;
    $this->Validations = $Validations;
    $this->setAttributeDefaults(array('class' => 'pacmec-input'));
		$value = $this->submitedValue();
    if($value !== NULL){
    	$this->setAttrib('value', $value);
    }
		parent::__construct('text', $this->Attribs);
	}

  public static function withNameAndValue($Name, $Value = '', $MinLength = 1, $Validations = array(), $Attribs = array())
	{
  	$FieldValidations = ($MinLength > 0) ?
  		array_merge($Validations, array(new Validation\LengthValidation($MinLength))) :
  		$Validations;
  	return new Number(
			array_merge(array('name' => $Name, 'min' => $MinLength, 'value' => $Value), $Attribs),
			$FieldValidations
		);
  }

}
