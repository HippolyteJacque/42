<?php

namespace E03Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use E01Bundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/ex03")
     */
    public function ex03()
    {
    	$user = $this->get('security.token_storage')->getToken()->getUser();
    	if ( $user == "anon."){
    		$content = "<h1>log in to access this page</h1>";
    	}
    	else {
			$em = $this->get('doctrine.orm.default_entity_manager');
    		$data = (array)$em->getRepository(Post::class)->findBy([], ['created' => 'DESC']);
    		if (!empty($data)){
    			$content = "<table>";
				foreach ($data as $key => $row) {
					$id = $row->getId();
					$title = $row->getTitle();
					$created = date_format($row->getCreated(), 'Y-m-d');
					$author = $row->getAuthor();
					$content .= "<tr>
						<td><a href='/getpost/".$id."'>".$title."</a></td>
						<td>".$author."</td>
						<td>".$created."</td>
					</tr>";
				}
				$content .= "</table>";
    		}
    		$content .= "<a href='/post'>post your own !</a>";
    	}
        return $this->render('E02Bundle:Default:index.html.twig', array("content" => $content));
    }

    /**
     * @Route("/getpost/{id}")
     */
    public function getpost($id)
    {
    	$user = $this->get('security.token_storage')->getToken()->getUser();
    	if ( $user == "anon."){
    		$content = "<h1>log in to access this page</h1>";
    	}
    	else {
			$em = $this->get('doctrine.orm.default_entity_manager');
    		$data = $em->getRepository(Post::class)->findOneBy(['id' => $id], ['created' => 'DESC']);
    		if (!empty($data)){
    			$content = "<ul>";
				$title = $data->getTitle();
				$cont = $data->getContent();
				$created = date_format($data->getCreated(), 'Y-m-d');
				$author = $data->getAuthor();
				$content .= "<li>title : ".$title."</li>
							<li>content : ".$cont."</li>
							<li>created : ".$created."</li>
							<li>author : ".$author."</li>";
				
				$content .= "</ul>";
    		}
    	}
        return $this->render('E02Bundle:Default:index.html.twig', array("content" => $content));
    }
}
