<?php

namespace D07Bundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Ex03Extension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('uppercaseWords', [$this, 'uppercaseWords']),
        ];
    }

    public function uppercaseWords($str)
    {
        $words = explode(" ", $str);
        foreach ($words as $key => $value) {
        	$words[$key] = ucfirst($value);
        }
        $str = implode(" ",$words);
        return $str;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('countNumbers', array($this, 'countNumbers')),
        );
    }

    public function countNumbers($str)
    {
    	$numbers = 0;
    	for ($i=0; $i < strlen($str); $i++){
    		if(is_numeric($str[$i])){
			    $numbers++;
			}
    	}
        return $numbers;
    }
}

?>