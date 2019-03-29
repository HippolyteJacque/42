<?php

namespace E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/e01", name="list")
     */
    public function indexAction()
    {
    	$cats = array('cache', 'controller', 'doctrine', 'forms', 'routing', 'security', 'services', 'templating', 'testing', 'translations', 'validation');

		$str = "<ul>";
    	foreach ($cats as $key => $cat) {
    		$str .= "<li><a href='/e01/".$cat."'>".$cat."</a></li>";
    	}
    	$str .= "</ul>";

        #return $this->render('https://github.com/davidpv/symfony2cheatsheet/blob/master/forms.html.twig', array('cat' => $cat));
    	return $this->render('E01Bundle:Default:base.html.twig', array('content' => $str));
    }

    /**
     * @Route("/e01/{cat}", name="onecat")
     */
    public function catAction($cat)
    {
    	$cats = array('cache', 'controller', 'doctrine', 'forms', 'routing', 'security', 'services', 'templating', 'testing', 'translations', 'validation');

    	if ( in_array($cat, $cats)){

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,"https://raw.githubusercontent.com/davidpv/symfony2cheatsheet/master/".$cat.".html.twig");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
            $content = curl_exec($ch);
            curl_close($ch);

    		return $this->render('E01Bundle:Default:base.html.twig', array('content' => $content));
    	}
    	else {
    		$str = "<ul>";
	    	foreach ($cats as $key => $cat) {
	    		$str .= "<li><a href='/e01/".$cat."'>".$cat."</a></li>";
	    	}
	    	$str .= "</ul>";
	    	return $this->render('E01Bundle:Default:base.html.twig', array('content' => $str));
    	}
    	return $this->render('E01Bundle:Default:base.html.twig', array('content' => $cat));
    }
}

class RedirectingController extends Controller
{
    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"})
     */
    public function removeTrailingSlashAction(Request $request)
    {
        // ...
    }

}
