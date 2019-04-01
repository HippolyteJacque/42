<?php

namespace ex05Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Mapping as ORM;
use ex05Bundle\Entity\usercinq;

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
     * @Route("/ex05")
     */
    public function ex05()
    {
    	$cmd_string = "php ../app/console doctrine:schema:update --force";
		$result = array();
		exec($cmd_string, $result);
		if ( !empty($result) && ($result[0] == "Nothing to update - your database is already in sync with the current entity metadata." || $result[1] == "Database schema updated successfully! \"1\" query was executed") ){
			
			$em = $this->get('doctrine.orm.default_entity_manager');
    		$data = (array)$em->getRepository(usercinq::class)->findAll();
    		if (!empty($data)){
    			$content = "<table>";
				foreach ($data as $key => $row) {
					$id = $row->getId();
					$row = (array)$row;
					$content .= "<tr>";
					foreach ($row as $key => $value) {
						$user = new usercinq();
						if (gettype($value) == "object"){
							$content .= "<td>".date_format($value, 'Y-m-d')."</td>";
						}
						else {
							$content .= "<td>".(string)$value."</td>";
						}
					}
					$content .= "<td><a href='/ex05/delete/".$id."'>delete</a></td></tr>";
				}
				$content .= "</table>";
    		}
			return $this->render('ex05Bundle:Default:index.html.twig', array("content" => $content));
		}
        return $this->render('ex00Bundle::index.html.twig');
    }

    /**
     * @Route("/ex05/delete/{id}")
     */
    public function removeEntry($id)
    {
    	$content = "";
    	if ( $id !== NULL){
    		if ( $user = $this->getDoctrine()->getRepository(usercinq::class)->findOneBy(['id' => $id]) ){
    			$em = $this->get('doctrine.orm.default_entity_manager');
    			$em->remove($user);
				$em->flush();
    			if ( $this->getDoctrine()->getRepository(usercinq::class)->findOneBy(['id' => $id]) == NULL){

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
    	return $this->render('ex05Bundle:Default:index.html.twig', array('content' => $content) );
    }
}