<?php

class Elem {
	
	public $element;
	public $content;
	public $attributes;

	function __construct($element, $content = NULL, $attributes = NULL){
		if (strpos(" meta img hr br html head body title h1 h2 h3 h4 h5 h6 p span div table tr th td ul ol li ", " ".$element." ") !== false ){
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
		elseif ( strpos(" meta img hr br ", " ".$ELEM->element." ") !== false ) {
			$this->content .= "\n\t<".$ELEM->element.$ELEM->attributes.">\n";
		}
	}

	public function getHTML(){
		if ( strpos(" meta img hr br ", " ".$this->element." ") !== false ) {
			return "<".$this->element.$this->attributes.">";
		}
		else {
			return "<".$this->element.$this->attributes.">".$this->content."</".$this->element.">\n";
		}
	}

	public function validPage(){
		if ( strpos($this->element, "html") === false ){
			# no html elem parent tag
			#var_dump('1');
			return false;
		}
		else if ( substr_count($this->content, "head") != 2 ){
			# no head or more than 1 head elem
			#var_dump('2');
			return false;
		}
		else if ( substr_count($this->content, "body") != 2 ){
			# no body or more than 1 head elem
			#var_dump('3');
			return false;
		}
		else if ( strpos($this->content, "<head>") > strpos($this->content, "<body>") ){
			# body elem coming before head elem
			#var_dump('4');
			return false;
		}
		else if ( strpos($this->content, "<head>") < strpos($this->content, "<body>") && strpos($this->content, "</head>") > strpos($this->content, "</body>") ){
			# body elem inside head elem
			#var_dump('5');
			return false;
		}
		else if ( substr_count($this->content, "title") > 2 ){
			# too may titles
			#var_dump('6');
			return false;
		}
		else if ( strpos($this->content, "<title>") !== false && (strpos($this->content, "<head>") > strpos($this->content, "<title>") || strpos($this->content, "</head>") < strpos($this->content, "</title>")) ){
			# title outside head tags
			#var_dump('7');
			return false;
		}
		else if ( substr_count($this->content, "charset") > 1){
			# charset meta tags used multiple time
			#var_dump('8');
			return false;
		}
		if ( strpos($this->content, "<p>") !== false ){
			# no tags inside p brackets
			$nop = split('<p>', $this->content);
			foreach ($nop as $key => $value) {
				if ( $key % 2 != 0) {
					$val = split('</p>', $value);
					if ($val[0] != strip_tags($val[0])){
						#var_dump('9');
						return false;
					}
				}
			}
		}
		if ( strpos($this->content, "<table>") !== false ){
			$tags = array("meta", "img", "hr", "br", "html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "span", "div", "table", "tr", "th", "td", "ul", "ol", "li", "p");
			# no tags inside p brackets
			$n = split('table>', $this->content);
			foreach ($n as $key => $value) {
				if ($key % 2 != 0){
					foreach ($tags as $k => $val) {
						if ($val != "tr" && $val != "th" && $val != "td" && strpos($value, "<".$val.">") !== false){
							#var_dump('10');
							return false;
						}
					}
				}	
			}
		}
		if ( strpos($this->content, "<ul>") !== false){
			$tags = array("meta", "img", "hr", "br", "html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "span", "div", "table", "tr", "th", "td", "ul", "ol", "li", "p");
			# no tags inside p brackets
			$n = split('ul>', $this->content);
			foreach ($n as $key => $value) {
				if ($key % 2 != 0){
					foreach ($tags as $k => $val) {
						if ($val != "li" && $val != "ul" && $val != "ol" && strpos($value, "<".$val.">") !== false){
							#var_dump('11');
							return false;
						}
					}
				}	
			}
		}
		if ( strpos($this->content, "<ol>") !== false){
			$tags = array("meta", "img", "hr", "br", "html", "head", "body", "title", "h1", "h2", "h3", "h4", "h5", "h6", "span", "div", "table", "tr", "th", "td", "ul", "ol", "li", "p");
			# no tags inside p brackets
			$n = split('ul>', $this->content);
			foreach ($n as $key => $value) {
				if ($key % 2 != 0){
					foreach ($tags as $k => $val) {
						if ($val != "li" && $val != "ul" && $val != "ol" && strpos($value, "<".$val.">") !== false){
							#var_dump('12');
							return false;
						}
					}
				}	
			}
		}
		return true;
	}
}

?>