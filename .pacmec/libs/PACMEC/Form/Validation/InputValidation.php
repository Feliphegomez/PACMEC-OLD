<?php

namespace PACMEC\Form\Validation;

interface InputValidation
{

    /**
     * @param string $inputValue
     * @return boolean
     */
    public function isValid($inputValue);

    /**
     * @return string
     */
    public function errorMessage();

}
?>
