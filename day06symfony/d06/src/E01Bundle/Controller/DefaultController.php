<?php

namespace E01Bundle\Controller;

use E01Bundle\Form\UserType;
use E01Bundle\Entity\users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $cmd_string = "php ../app/console doctrine:schema:update --force";
        $result = array();
        exec($cmd_string, $result);
        if ( !empty($result) && ($result[0] == "Nothing to update - your database is already in sync with the current entity metadata." || $result[1] == "Database schema updated successfully! \"1\" query was executed") ){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if ( $user == "anon."){
                $content = "<a href='/register'>register</a><br><a href='/login'>login</a>";
            }
            else {
                $content = "<h1>Welcome ".$user->getUsername()."</h1><br><a href='/logout'>logout</a>";
            }
            return $this->render('E01Bundle:Default:index.html.twig', array("content" => $content));
        }
        return $this->render('E01Bundle:Default:index.html.twig', array("content" => ""));
    }

    /**
     * @Route("/logout")
     */
    public function logout()
    {
    	$this->get('security.token_storage')->setToken(null);
		$this->get('request')->getSession()->invalidate();
		return $this->redirectToRoute('home');
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
