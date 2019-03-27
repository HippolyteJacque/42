<?php

include "TemplateEngine.php";
include "Elem.php";
include "MyException.php";

$html = new Elem('html');
$body = new Elem('body');
$head = new Elem('head');
$title = new Elem('title');
$meta = new Elem('meta', NULL, ['charset' => 'uft-8']);
$p = new Elem('p');
$span = new Elem('span');
$table = new Elem('table');
$ul = new Elem('ul');
$li = new Elem('li');


/*
	no html elem parent tag
*/
$body->pushElement($html);
$body->validPage();
$body = new Elem('body');

/*
	more than one head test
*/
$html->pushElement($head);
$html->pushElement($head);
$html->validPage();
$html = new Elem('html');

/*
	body before head test
*/
$html->pushElement($body);
$html->pushElement($head);
$html->validPage();
$html = new Elem('html');


/*
	body elem inside head elem
*/
$head->pushElement($body);
$html->pushElement($head);
$html->validPage();
$html = new Elem('html');
$head = new Elem('head');

/*
	too may titles
*/
$head->pushElement($title);
$head->pushElement($title);
$html->pushElement($head);
$html->pushElement($body);
$html->validPage();
$html = new Elem('html');
$head = new Elem('head');

/*
	title outside head tags
*/
$html->pushElement($head);
$html->pushElement($body);
$html->pushElement($title);
$html->validPage();
$html = new Elem('html');

/*
	charset meta tags used multiple time
*/
$head->pushElement($meta);
$head->pushElement($meta);
$html->pushElement($head);
$html->pushElement($body);
$html->validPage();
$html = new Elem('html');
$head = new Elem('head');

/*
	tags inside p brackets	
*/
$html->pushElement($head);
$html->pushElement($body);
$p->pushElement($span);
$html->pushElement($p);
$html->validPage();
$html = new Elem('html');
$p = new Elem('p');

/*
	table only tr
*/
$table->pushElement($span);
$table->pushElement($p);
$html->pushElement($head);
$html->pushElement($body);
$html->pushElement($table);
$html->validPage();
$html = new Elem('html');
$table = new Elem('table');

/*
	ul only li
*/
$ul->pushElement($li);
$ul->pushElement($p);
$html->pushElement($head);
$html->pushElement($body);
$html->pushElement($ul);
$html->validPage();
$html = new Elem('html');
$ul = new Elem('ul');

$engine = new TemplateEngine($html);
$engine->createFile("ex05.html");
?>