<?php


namespace PACMEC\Form;


class StaticText extends FormElement
{
    public function __construct($Text, $Attribs = array())
    {
        $this->Attribs = $Attribs;
        $this->setAttributeDefaults(array('class' => 'pacmec-left-align'));

        $this->Code = '<p' . $this->parseAttribs($this->Attribs) . '>' . $Text . '</p>';
    }
}
