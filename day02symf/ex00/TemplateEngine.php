<?php

class TemplateEngine
{	
	public function createFile($fileName, $templateName, $parameters){

		if (file_exists($templateName)){
			$template = file_get_contents($templateName);

			if ($template){
				foreach ($parameters as $key => $value) {
					$template = str_replace("{".$key."}", $value, $template);
				}

				file_put_contents($fileName, $template);
			}
		}
	}
}

?>