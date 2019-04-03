<?php

namespace D07Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;


class Ex02Controller extends Controller
{
	
	/**
     * @Route("/{_locale}/ex02/{count}")
     * @Route("/{_locale}/ex02/")
     */
	public function translationsAction($count = 0)
	{
		if ($count > 9 || (intval($count) == 0 && $count != '0')){
			throw new OutOfBoundsException("valeurs possibles dans l’intervalle 0 à 9");
		}
		$number = $this->container->getParameter('d07.number');
		return $this->render('D07Bundle::ex02.html.twig', array("number" => $number, "count" => $count));
	}
}

?>