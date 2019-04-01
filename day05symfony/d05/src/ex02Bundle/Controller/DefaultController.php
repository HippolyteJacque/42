<?php

namespace ex02Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
     * @Route("/ex02")
     */
    public function ex02(Request $request)
    {
    	$sql = "CREATE TABLE IF NOT EXISTS userex02 (
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
        
        $form = $this->showForm();

        $form->handleRequest($request);

	    if ( $form->isSubmitted() && $form->isValid() ) {
	        // data is an array with "username", "name", "email", "birthday", "address"
	        $data = $form->getData();

	        $sql = "SELECT * from userex02 WHERE username = :username OR email = :email";
			/** @var EntityManager $em */
			$em = $this->get('doctrine.orm.default_entity_manager');
			$statement = $em->getConnection()->prepare($sql);
			$statement->bindValue(':username', $data['username']);
			$statement->bindValue(':email', $data['email']);
			$result = $statement->execute();
			$row = $statement->fetch();
			if (empty($row)){
				$sql = "INSERT INTO userex02 (`username`, `name`, `email`, `enable`, `birthday`, `address`) VALUES (:username,:name,:email,:enable,:birthday,:address)";
				/** @var EntityManager $em */
				$em = $this->get('doctrine.orm.default_entity_manager');
				$statement = $em->getConnection()->prepare($sql);
				$statement->bindValue(':username', $data['username']);
				$statement->bindValue(':name', $data['name']);
				$statement->bindValue(':email', $data['email']);
				$statement->bindValue(':enable', (int)$data['enable']);
				$statement->bindValue(':birthday', date_format($data['birthday'], 'Y-m-d'));
				$statement->bindValue(':address', $data['address']);
				$result = $statement->execute();
			}
	    }

	    $content = "<a href='/Showdata'>see the user table data</a>";

        return $this->render('ex02Bundle:Default:index.html.twig', array('form' => $form->createView(), 'content' => $content) );
    }

    /**
     * @Route("/Showdata", name="showData")
     */
    public function showData()
    {
    	$sql = "SELECT * from userex02";
		/** @var EntityManager $em */
		$em = $this->get('doctrine.orm.default_entity_manager');
		$statement = $em->getConnection()->prepare($sql);
		$result = $statement->execute();
		$users = $statement->fetchAll();

		$str = "<table>";
		if (!empty($users)){
			foreach ($users as $key => $userdata) {
				$str .= "<tr>";
				foreach ($userdata as $key => $value) {
					$str .= "<td>".$value."</td>";
				}
				$str .= "</tr>";
			}
		}
		$str .= "</table>";
		return $this->render('ex00Bundle::index.html.twig', array('content' => $str) );
    }

    public function showForm(){
    	$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
		->add('username', TextType::class)
        ->add('name', TextType::class)
        ->add('email', EmailType::class)
        ->add('enable', CheckboxType::class, array(
		    'label'    => 'enable?',
		    'required' => false,
		))
        ->add('birthday', DateType::class, array(
		    // this is actually the default format for single_text
		    'format' => 'yyyy-MM-dd',
		))
        ->add('address', TextType::class)
        ->getForm();
        return $form;
    }

}
