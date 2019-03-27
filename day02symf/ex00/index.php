<?php

include "TemplateEngine.php";

$obj = new TemplateEngine;
$obj->createFile("ex00.html", "book_description.html", array('nom' => 'ex00', 'auteur' => 'hippo', 'description' => 'la vie a un prix', 'prix' => 42) );

?>