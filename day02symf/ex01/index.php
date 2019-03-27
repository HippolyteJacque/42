<?php

include "TemplateEngine.php";
include "Text.php";

$obj = new TemplateEngine;
$obj->createFile("ex01.html", new Text(array("hey", "yo")));

?>