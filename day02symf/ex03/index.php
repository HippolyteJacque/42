<?php

include "TemplateEngine.php";
include "Elem.php";

$html = new Elem('html');
$head = new Elem('head');
$title = new Elem('title', 'ex03');
$linebreak = new Elem('br');
$body = new Elem('body');
$body->pushElement(new Elem('p', 'Lorem ipsum'));
$body->pushElement($linebreak);
$head->pushElement($title);
$html->pushElement($head);
$html->pushElement($body);

$engine = new TemplateEngine($html);
$engine->createFile("ex03.html");
?>