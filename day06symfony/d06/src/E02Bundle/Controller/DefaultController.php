<?php

namespace E02Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use E01Bundle\Form\UserType;
use E01Bundle\Entity\users;

class DefaultController extends Controller
{
    /**
     * @Route("/ex02")
     */
    public function indexAction()
    {
    	$user = $this->get('security.token_storage')->getToken()->getUser();
    	if ( $user == "anon."){
    		$content = "<h1>log in to access this page</h1>";
    	}
    	else {
    		$roles = $user->getRoles();
    		if (in_array("ROLE_ADMIN", $roles)){
    			$content = "<h1>hello admin</h1>";
    			$admin_id = $user->getId();

    			$em = $this->get('doctrine.orm.default_entity_manager');
	    		$data = (array)$em->getRepository(users::class)->findAll();
	    		if (!empty($data)){
	    			$content = "<table>";
					foreach ($data as $key => $row) {
						$id = $row->getId();
						$username = $row->getUsername();
						$row = (array)$row;
						$content .= "<tr>";
						$content .= "<td>".$username."</td>";
						if ($admin_id == $id){
							$content .= "</tr>";
						}
						else {
							$content .= "<td><a href='/ex02/delete/".$id."'>delete</a></td></tr>";
						}
					}
					$content .= "</table>";
	    		}

    		}
    		else {
    			$content = "<h1>require admin</h1>";
    		}
    	}
        return $this->render('E02Bundle:Default:index.html.twig', array("content" => $content));
    }

    /**
     * @Route("/ex02/delete/{id}")
     */
    public function removeEntry($id)
    {
    	$content = "";
    	if ( $id !== NULL){
    		if ( $user = $this->getDoctrine()->getRepository(users::class)->findOneBy(['id' => $id]) ){
    			$em = $this->get('doctrine.orm.default_entity_manager');
    			$em->remove($user);
				$em->flush();
    			if ( $this->getDoctrine()->getRepository(users::class)->findOneBy(['id' => $id]) == NULL){

					$content = "<h3>success !</h3>";
				}
				else {
					$content = "<h3>failed !</h3>";
				}
			}
			else {
				$content = "<h3>unknow id</h3>";
			}
    	}
    	else {
    		$content = "<h3>non existing id !</h3>";
    	}
    	return $this->render('E02Bundle:Default:index.html.twig', array('content' => $content) );
    }

}

