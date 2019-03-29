<?php

namespace E03Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/e03")
     */
    public function indexAction()
    {
    	$lines = $this->container->getParameter('e03.number_of_colors');
    	$gradient = 255/$lines;
    	$black = 0;
    	$other = 255;
    	$table = "<table>";
    	for ($i=0; $i < $lines; $i++) {
    		$table .= "<th style='background-color: rgb(".$black.",".$black.",".$black."); height: 40px; width: 80px;'></th>
    					<th style='background-color: rgb(".$other.",0,0); height: 40px; width: 80px;'></th>
    					<th style='background-color: rgb(0,0,".$other."); height: 40px; width: 80px;'></th>
    					<th style='background-color: rgb(0,".$other.",0); height: 40px; width: 80px;'></th>
    				<tr>";
    		$other -= $gradient;
    		$black += $gradient;
    	}
    	$table .= "</table>";
        return $this->render('E03Bundle::ex03.html.twig', array("table" => $table));
    }
}
