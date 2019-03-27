<?php

class Elem {
	
	public $element;
	public $content;
	public $attributes;

	function __construct($element, $content = NULL, $attributes = NULL){
		if (strpos(" meta img hr br html head body title h1 h2 h3 h4 h5 h6 p span div table tr th td ul ol li ", " ".$element." ")){
			$this->element = $element;
			$this->content = $content;
			if ($attributes && gettype($attributes) == "array"){
				foreach ($attributes as $key => $value) {
					$this->attributes .= " ".$key."='".$value."'";
				}
			}
		}
		else {
			new MyException();
		}
	}

	public function pushElement( $ELEM ){
		if ( strpos(" html head body title h1 h2 h3 h4 h5 h6 p span div table tr th td ul ol li ", " ".$ELEM->element." ") ){
			$this->content .= "\n\t<".$ELEM->element.$ELEM->attributes.">\n\t\t".$ELEM->content."\n\t</".$ELEM->element.">\n";
		}
		elseif ( strpos(" meta img hr br ", " ".$ELEM->element." ") ) {
			$this->content .= "\n\t<".$ELEM->element.$ELEM->attributes.">\n";
		}
	}

	public function getHTML(){
		if ( strpos(" meta img hr br ", " ".$this->element." ") ) {
			return "<".$this->element.$this->attributes.">";
		}
		else {
			return "<".$this->element.$this->attributes.">".$this->content."</".$this->element.">\n";
		}
	}
}

?>