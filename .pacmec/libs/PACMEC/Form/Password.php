<?php
namespace PACMEC\Form;

class Password extends GeneralInput implements Validable
{
  public function __construct($Attribs = array(), $Validations = array())
  {
    $this->Attribs = $Attribs;
    $this->Validations = $Validations;
    $this->setAttributeDefaults(array('class' => 'pacmec-input'));




    parent::__construct('password', $this->Attribs);
  }

  public static function withNameAndValue($Name, $Value = '', $Maxlength = 255, $Validations = array(), $Attribs = array())
	{
  	$FieldValidations = ($Maxlength > 0) ?
  		array_merge($Validations, array(new Validation\LengthValidation($Maxlength))) :
  		$Validations;
  	return new Password(
			array_merge(array('name' => $Name, 'maxlength' => $Maxlength, 'value' => $Value), $Attribs),
			$FieldValidations
		);
  }

}
