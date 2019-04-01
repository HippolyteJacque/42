<?php

namespace E04Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/ex04")
     */
    public function indexAction()
    {
    	$animals = array("dog", "cat", "dolphin", "horse", "turtle");
    	$animal = array_rand($animals);

    	$session = $this->get('session');
    	$sessionBag = $session->getMetadataBag();
		$lastrq = $sessionBag->getLastUsed();
		$lastrq = abs($lastrq - time());
		$creation = $sessionBag->getCreated();
		$creation = abs($creation - time());
    	$user = $session->get('name');
    	$content = "";
    	if ($creation > 60){
    		$key = array_search($user, $animals);
    		unset($animals[$key]);
    		$animal = array_rand($animals);
			session_destroy();
			session_start();
			$session = $this->get('session');
			session_set_cookie_params(60);
			$session->set('name', 'anonymous '.$animals[$animal]);
			$user = $session->get('name');
		}
		else if (!$user) {
			$session = $this->get('session');
			session_set_cookie_params(60);
			$session->set('name', 'anonymous '.$animals[$animal]);
			$user = $session->get('name');
		}
		$content .= "<h1>Hello ".$user."</h1><br>";
		$content .= "seconds since last resquest: ".$lastrq;
        return $this->render('E04Bundle:Default:index.html.twig', array("content" => $content));
    }
}
