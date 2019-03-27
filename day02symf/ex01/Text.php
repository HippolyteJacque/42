<?php

class Text
{
	public $data;

	function __construct($data){
		if ($data){
			$this->data = $data;
		}
	}
	public function add_string($new_str){
		array_push($this->data, $new_str);
	}
	public function show_data(){
		foreach ($this->data as $key => $value) {
			$str .= "<p>".$value."</p>";
		}
		return $str;
	}
}

?>