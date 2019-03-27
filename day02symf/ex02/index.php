<?php

include "TemplateEngine.php";
include "HotBeverage.php";
include "Tea.php";
include "Coffee.php";

$obj = new TemplateEngine;
$tea = new Tea;
$coffee = new Coffee;

$obj->createFile($tea);
$obj->createFile($coffee);
?>