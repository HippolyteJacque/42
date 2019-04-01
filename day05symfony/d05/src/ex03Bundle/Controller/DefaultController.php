<?php

namespace ex03Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Mapping as ORM;
use ex03Bundle\Entity\usertrois;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/ex03")
     */
    public function indexAction()
    {
    	$content = "<a href='/Showdata3'>see the user table data</a>";
    	$cmd_string = "php ../app/console doctrine:schema:update --force";
		$result = array();
		exec($cmd_string, $result);
		if ( !empty($result) && ($result[0] == "Nothing to update - your database is already in sync with the current entity metadata." || $result[1] == "Database schema updated successfully! \"1\" query was executed") ){
			$user = new usertrois();
			$form = $this->formit($user);
			$request = $this->get('request');
			$form->handleRequest($request);
			if ( $form->isSubmitted() && $form->isValid() ){
				$username = $form->get('username')->getData();
				$name = $form->get('name')->getData();
				$email = $form->get('email')->getData();
				$enable = $form->get('enable')->getData();
				$birthday = $form->get('birthday')->getData();
				$address = $form->get('address')->getData();
				$user->setUsername($username);
				$user->setName($name);
				$user->setEmail($email);
				$user->setEnable($enable);
				$user->setBirthday($birthday);
				$user->setAddress($address);

				if ( $this->getDoctrine()->getRepository(usertrois::class)->findOneBy(['username' => $username]) || $this->getDoctrine()->getRepository(usertrois::class)->findOneBy(['email' => $email])){

				}
				else {
					$em = $this->get('doctrine.orm.default_entity_manager');
					$em->persist($user);
					$em->flush();
				}
			}
			return $this->render('ex03Bundle:Default:index.html.twig', array("form" => $form->createView(), "content" => $content));
		}
        return $this->render('ex00Bundle::index.html.twig', array("content" => $content));
    }

    public function formit(usertrois $user){
    	$form = $this->createFormBuilder($user)
		->add('username', 'text')
		->add('name', 'text')
		->add('email', 'email')
		->add('enable', 'checkbox', array(
		    'label'    => 'enable?',
		    'required' => false,
		))
		->add('birthday', 'date', array(
		    // this is actually the default format for single_text
		    'format' => 'yyyy-MM-dd',
		))
		->add('address', 'text')
		->getform();
        return $form;
    }

    /**
     * @Route("/Showdata3", name="showD")
     */
    public function showD()
    {
    	$em = $this->get('doctrine.orm.default_entity_manager');
    	$records = (array)$em->getRepository(usertrois::class)->findAll();
		$str = "<table>";
		if (!empty($records)){
			foreach ($records as $key => $userdata) {
				$userdata = (array)$userdata;
				$str .= "<tr>";
				foreach ($userdata as $key => $value) {
					if (gettype($value) == "object"){
						$str .= "<td>".date_format($value, 'Y-m-d')."</td>";
					}
					else {
						$str .= "<td>".(string)$value."</td>";
					}
				}
				$str .= "</tr>";
			}
		}
		$str .= "</table>";
		return $this->render('ex00Bundle::index.html.twig', array('content' => $str) );
    }
}
