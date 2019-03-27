<?php

class Tea extends HotBeverage {

	public $name = "tea";
	public $price = "9000";
	public $resistence = "les plus forts";

	private $description = "le tea cest bon";
	private $comment = "on peut en abuser";

	public function get_description(){
		return $this->description;
	}
	public function get_comment(){
		return $this->comment;
	}
}

?>