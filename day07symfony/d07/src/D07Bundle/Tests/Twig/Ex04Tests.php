<?php

namespace D07Bundle\Tests\Twig;

use D07Bundle\Twig\Ex03Extension;
use PHPUnit\Framework\TestCase;

class Ex04Tests extends TestCase
{
    public function testUppercaseWords1()
    {
        $testing = new Ex03Extension();
        $result = $testing->uppercaseWords("vive l'école 42 !");
        $this->assertEquals("Vive L'école 42 !", $result);
    }

    public function testUppercaseWords2()
    {
        $testing = new Ex03Extension();
        $result = $testing->uppercaseWords("42 c'est super cool !");
        $this->assertEquals("42 C'est Super Cool !", $result);
    }

    public function testUppercaseWords3()
    {
        $testing = new Ex03Extension();
        $result = $testing->uppercaseWords("j'adore 42 ya une super ambiance !");
        $this->assertEquals("J'adore 42 Ya Une Super Ambiance !", $result);
    }

    public function testCountNumbers1()
    {
        $testing = new Ex03Extension();
        $result = $testing->countNumbers("vive l'école 42 !");
        $this->assertEquals(2, $result);
    }

    public function testCountNumbers2()
    {
        $testing = new Ex03Extension();
        $result = $testing->countNumbers("combien font 21 + 21 ?");
        $this->assertEquals(4, $result);
    }

    public function testCountNumbers3()
    {
        $testing = new Ex03Extension();
        $result = $testing->countNumbers("l'école 42 a ouvert en 2013");
        $this->assertEquals(6, $result);
    }
}

?>