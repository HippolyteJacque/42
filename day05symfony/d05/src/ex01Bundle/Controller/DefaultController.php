<?php

namespace ex01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Mapping as ORM;

class DefaultController extends Controller
{
    /**
     * @Route("/ex01")
     */
    public function ex01()
    {
        $str = "<a href='/createtableorm'>Create Table ORM</a>";
        return $this->render('ex00Bundle::index.html.twig', array("content" => $str));
    }

    /**
     * @Route("/createtableorm", name="createTableOrm")
     */
    public function createTableOrm()
    {
    	$cmd_string = "php ../app/console doctrine:schema:update --force";
		$result = array();
		exec($cmd_string, $result);
		if ( !empty($result) && ($result[0] == "Nothing to update - your database is already in sync with the current entity metadata." || $result[1] == "Database schema updated successfully! \"1\" query was executed") ){
			$str = "<h2>Success !</h2>";
		}
		else {
			$str = "<h2>failed !</h2>";
		}
        return $this->render('ex00Bundle::index.html.twig', array("content" => $str));
    }
}
