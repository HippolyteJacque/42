<?php

namespace ex04Bundle\Controller;

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
     * @Route("/ex04")
     */
    public function ex04()
    {
    	$sql = "CREATE TABLE IF NOT EXISTS userex04 (
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
        
        $sql = "SELECT * FROM userex04";
		/** @var EntityManager $em */
		$em = $this->get('doctrine.orm.default_entity_manager');
		$statement = $em->getConnection()->prepare($sql);
		$result = $statement->execute();
		$data = $statement->fetchAll();
		$content = "<table>";
		foreach ($data as $key => $row) {
			$content .= "<tr>";
			foreach ($row as $key => $value) {
				$content .= "<td>".$value."</td>";
			}
			$content .= "<td><a href='/ex04/delete/".$row['id']."'>delete</a></td></tr>";
		}
		$content .= "</table>";

        return $this->render('ex04Bundle:Default:index.html.twig', array('content' => $content) );
    }

    /**
     * @Route("/ex04/delete/{id}")
     */
    public function removeEntry($id)
    {
    	if ( $id ){
    		$sql = "SELECT * FROM userex04 WHERE ID = :id";
			/** @var EntityManager $em */
			$em = $this->get('doctrine.orm.default_entity_manager');
			$statement = $em->getConnection()->prepare($sql);
			$statement->bindValue(':id', (int)$id);
			$result = $statement->execute();
			$data = $statement->fetchAll();
			if ( $data ){
				$sql = "DELETE FROM userex04 WHERE ID = :id";
				/** @var EntityManager $em */
				$em = $this->get('doctrine.orm.default_entity_manager');
				$statement = $em->getConnection()->prepare($sql);
				$statement->bindValue(':id', (int)$id);
				$result = $statement->execute();
				if ($result == true){
					$content = "<h3>success !</h3>";
				}
				else {
					$content = "<h3>failed !</h3>";
				}
			}
			else {
				$content = "<h3>non existing id !</h3>";
			}
    	}
    	return $this->render('ex04Bundle:Default:index.html.twig', array('content' => $content) );
    }
}
