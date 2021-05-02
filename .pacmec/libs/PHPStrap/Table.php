<?php
namespace PHPStrap;

class Table
{

	private $Contents = array(), $Headers = array(), $HeadersStyles = array(), $Styles, $HeaderColumns, $Attributes;

	public function addHeaderRow($Row){
		$this->Headers[] = $Row;
	}

	public function setStylesHeader(array $Styles){
		$this->HeadersStyles = $Styles;
	}

	public function addRow($Row){
		$this->Contents[] = $Row;
	}

	public function __construct($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array(), $Attributes = array()){
		if(!empty($Content)){
			if($HeaderRows > 0){
				$this->Headers = array_slice($Content, 0, $HeaderRows);
				$this->Contents = array_slice($Content, $HeaderRows);
			}else{
				$this->Contents = $Content;
			}
		}
		$this->HeaderColumns = $HeaderColumns;
        $this->Attributes = $Attributes;
        $this->Styles = $Styles;
	}

	public function __toString(){
		$headers = '';
		if(!empty($this->Headers)){
			$_headers = '';
			foreach($this->Headers as $Header){
				$_headers .= '<tr><th>' . implode('</th><th>', $Header) . '</th></tr>';
			}
			$headers .= Util\Html::tag('thead', $_headers, $this->HeadersStyles);
		}
		$content = '';
		if(!empty($this->Contents)){
			$content .= '<tbody>';
			foreach($this->Contents as $Content){
				$content .= '<tr>';
				$colh = array_slice($Content, 0, $this->HeaderColumns);
				if(!empty($colh)){
					$content .= '<th>' . implode('</th><th>', $colh) . '</th>';
				}
				$col = array_slice($Content, $this->HeaderColumns);
				$content .= '<td>' . implode('</td><td>', $col) . '</td>';
				$content .= '</tr>';
			}
			$content .= '</tbody>';
		}
		return Util\Html::tag("table",
			$headers . $content,
			$this->Styles, $this->Attributes
		);
    }

    public static function basicTable($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array('table'), $Attributes = array()){
        return new Table($Content, $HeaderRows, $HeaderColumns, $Styles, $Attributes);
    }

    public static function hoverTable($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array(), $Attributes = array()){
        return new Table($Content, $HeaderRows, $HeaderColumns, array_merge(array('table', 'table-hover'), $Styles), $Attributes);
    }

	public static function stripedTable($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array(), $Attributes = array()){
    	return new Table($Content, $HeaderRows, $HeaderColumns, array_merge(array('table', 'table-striped'), $Styles), $Attributes);
    }

	public static function borderedTable($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array(), $Attributes = array()){
    	return new Table($Content, $HeaderRows, $HeaderColumns, array_merge(array('table', 'table-bordered'), $Styles), $Attributes);
    }

	public static function condensedTable($Content = array(), $HeaderRows = 0, $HeaderColumns = 0, $Styles = array(), $Attributes = array()){
    	return new Table($Content, $HeaderRows, $HeaderColumns, array_merge(array('table', 'table-condensed'), $Styles), $Attributes);
    }

}
