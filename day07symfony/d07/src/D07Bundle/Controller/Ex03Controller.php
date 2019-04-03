<?php

namespace D07Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;


class Ex03Controller extends Controller
{
	
	/**
     * @Route("/{_locale}/ex03")
     */
	public function extensionAction()
	{

		return $this->render('D07Bundle::ex03.html.twig');
	}
}

?>