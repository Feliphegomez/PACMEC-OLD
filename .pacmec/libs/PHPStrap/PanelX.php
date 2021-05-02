<?php
namespace PHPStrap;

class PanelX {
	private $Contents, $Buttons, $Headers;
	
	public function __construct($Content = "", $Buttons = ""){
		$this->Contents = array($Content);
		$this->Buttons = array($Buttons);
		$this->Headers = array();
	}
	
	public function addContent($Content){
		$this->Contents[] = $Content;
	}
	
	public function addButton($Button){
		$this->Buttons[] = $Button;
	}
	
	public function addHeader($Content){
		$this->Headers[] = $Content;
	}
	
	private $styles = array('x_panel');
	
	public function addStyle($styleName){
		$this->styles[] = $styleName;
	}
	
	private $bodyStyles = array('x_content');
	
	public function addBodyStyle($styleName){
		$this->bodyStyles[] = $styleName;
	}
	
	private $headingStyles = array('x_title');
	
	public function addHeadingStyle($styleName){
		$this->headingStyles[] = $styleName;
	}
	
	private $buttonsStyles = array('nav navbar-right panel_toolbox');
	
	public function addButtonsStyles($styleName){
		$this->buttonsStyles[] = $styleName;
	}
	
	
 	public function __toString(){	
 		$header = !empty($this->Headers) ? Util\Html::tag("div", Util\Html::tag("h2", implode($this->Headers)).(Util\Html::ul($this->Buttons, $this->buttonsStyles)).'<div class="clearfix"></div>', $this->headingStyles) : "";
		/*Pendiente agregar 
			<ul class="nav navbar-right panel_toolbox">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Settings 1</a></li>
						<li><a href="#">Settings 2</a></li>
					</ul>
				</li>
			</ul>
			*/

		
		
 		return Util\Html::tag("div",
			$header . Util\Html::tag("div",
				implode($this->Contents), 
				$this->bodyStyles
			),
			$this->styles
		);
    }
}