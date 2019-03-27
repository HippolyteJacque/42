<?php

class TemplateEngine
{	
	public function createFile(HotBeverage $text){

		if (file_exists("template.html")){
			$template = file_get_contents("template.html");

			if ($template){

				$className = get_class($text);
				$reflection = new ReflectionClass($className);

				$attributes = $reflection->getProperties();

				foreach ($attributes as $key => $value) {
					$template = str_replace("{".$value->name."}", $text->{"get_".$value->name}(), $template);
				}

				file_put_contents($className.".html", $template);
			}
		}
	}
}

?>