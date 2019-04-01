<?php

namespace ex00Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

	/**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ex00Bundle:Default:index.html.twig');
    }

    /**
     * @Route("/ex00")
     */
    public function ex00()
    {
    	$str = "<a href='/createtablesql'>Create Table</a>";
        return $this->render('ex00Bundle::index.html.twig', array("content" => $str));
    }

    /**
     * @Route("/createtablesql", name="createTableSql")
     */
    public function createTableSql()
    {
		$sql = "CREATE TABLE IF NOT EXISTS userex00 (
			    id INT(6) PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(30) UNIQUE,
			    name VARCHAR(30),
			    email VARCHAR(50) UNIQUE,
			    enable BOOLEAN,
			    birthday DATETIME,
			    address LONGTEXT)";
			    
		/** @var EntityManager $em */
		$em = $this->get('doctrine.orm.default_entity_manager');
		$statement = $em->getConnection()->prepare($sql);
		$result = $statement->execute();

		if ( $result == true ){
			$str = "<h2>Success !</h2>";
		}
		else {
			$str = "<h2>failed !</h2>";
		}
        return $this->render('ex00Bundle::index.html.twig', array("content" => $str));
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
