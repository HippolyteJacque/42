<?php

class TemplateEngine
{	
	public function createFile($fileName, Text $text){

		$head = 
		"<!DOCTYPE html>
				<html>
					<head>
						<title>ex01</title>
					</head>
					<body>";

		$foot = 	"</body>
				</html>";

		$text->add_string("wsh");
		file_put_contents($fileName, $head.$text->show_data().$foot);
	}
}

?>