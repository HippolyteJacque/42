<?php

class Coffee extends HotBeverage {

	public $name = "coffee";
	public $price = "42";
	public $resistence = "NONE";

	private $description = "le cafe cest bon";
	private $comment = "mais faut pas abuser";

	public function get_description(){
		return $this->description;
	}
	public function get_comment(){
		return $this->comment;
	}
}

?>