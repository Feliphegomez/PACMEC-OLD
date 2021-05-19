<?php
namespace PACMEC\Form;

class Label extends FormElement
{
    private $Text, $ScreenReaderOnly;

    /**
     * @param string $Text
     * @param array  $Attribs
     * @param bool   $ScreenReaderOnly
     * @param        $FormType
     * @param        $LabelWidth
     */
    public function __construct($Text, $Attribs = array(), $ScreenReaderOnly = false, $FormType, $LabelWidth=12)
    {
        $this->Attribs          = $Attribs;
        $this->Text             = $Text;
        $this->ScreenReaderOnly = $ScreenReaderOnly;

        if ($FormType === FormType::Horizontal) {
            $this->Attribs = $this->setAttributeDefaults(array('class' => 'pacmec-col s' . $LabelWidth));
        }

        if ($FormType === FormType::Inline && $ScreenReaderOnly === true) {
            $this->Attribs = $this->setAttributeDefaults(array('class' => 'sr-only'));
        }
    }

    /**
     * @return string
     */
    public function __toString(){
    	return '<label ' . $this->parseAttribs($this->Attribs) . '>' . $this->Text . '</label>';
    }

}
