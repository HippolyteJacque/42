<?php

include "TemplateEngine.php";
include "Elem.php";
include "MyException.php";

$html = new Elem('html');
$head = new Elem('head');
$title = new Elem('title', 'ex04');
$linebreak = new Elem('r');
$body = new Elem('body');
$body->pushElement(new Elem('p', 'Lorem ipsum', ['class' => 'text-muted', 'style'=> 'background-color : black;']));
$body->pushElement($linebreak);
$head->pushElement($title);
$html->pushElement($head);
$html->pushElement($body);

$engine = new TemplateEngine($html);
$engine->createFile("ex04.html");
?>