<?php
namespace PHPStrap;

class FontaIcon
{
	private $styles = array('fa');
	public function __construct($iconName, $vpro=""){
		$this->styles[] = "fa-{$vpro}" . $iconName;
	}

	public function addStyle($styleName){
		$this->styles[] = $styleName;
	}

 	public function __toString(){
    return Util\Html::tag("span", '', $this->styles);
  }

  public static function button($Icon, $Content = '', $Href = "#", $styles = array(), $Attribs = array()){
  	return Util\Html::tag("a",
  		Util\Html::tag("button", new Icon($Icon) . ' ' . $Content, array_merge(array('btn', 'btn-default'), $styles)),
  		array(), array_merge(array("href" => $Href), $Attribs));
  	return Util\Html::tag("span", '', array('fa', 'fa-' . $this->Icon));
  }
}
?>
