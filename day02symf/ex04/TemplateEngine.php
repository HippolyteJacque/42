<?php

class TemplateEngine
{
	public $template;
	function __construct(elem $template){
		$this->template = $template;
	}

	public function createFile($fileName){
		$template = $this->template->getHTML();
		file_put_contents($fileName, $template);

	}
}

?>