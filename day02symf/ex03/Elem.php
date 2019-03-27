<?php

class Elem {
	
	public $element;
	public $content;

	function __construct($element, $content = NULL){
		if (strpos(" meta img hr br html head body title h1 h2 h3 h4 h5 h6 p span div ", " ".$element." ")){
			$this->element = $element;
			$this->content = $content;
		}
	}

	public function pushElement( $ELEM ){
		if ( strpos(" html head body title h1 h2 h3 h4 h5 h6 p span div ", " ".$ELEM->element." ") ){
			$this->content .= "\n\t<".$ELEM->element.">\n\t\t".$ELEM->content."\n\t</".$ELEM->element.">\n";
		}
		elseif ( strpos(" meta img hr br ", " ".$ELEM->element." ") ) {
			$this->content .= "\n\t<".$ELEM->element.">\n";
		}
	}

	public function getHTML(){
		if ( strpos(" meta img hr br ", " ".$this->element." ") ) {
			return "<".$this->element.">";
		}
		else {
			return "<".$this->element.">".$this->content."</".$this->element.">\n";
		}
	}
}

?>