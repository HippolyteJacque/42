<?php

namespace E02Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/e02")
     */
    public function indexAction(Request $request)
    {
    	$file = $this->get('kernel')->getRootDir()."/../".$this->container->getParameter('e02file');
    	$form = $this->createFormBuilder()
	    	->add(
	    		'Message',
	    		TextType::class,
	    		[
	    			'required' => false
	    		]
	    	)
	    	->add(
	    		'Include_timestamp',
	    		ChoiceType::class,
	    		[
	    			'choices'  => [
				        'true' => "yes",
				        'false' => "no"
				    ]
	    		]
	    	)
	    	->getForm();
	   	$form->handleRequest($request);
	   	if ($form->isSubmitted() && $form->isValid()){
	   		$data = $form->getData();
	   		if (!empty($data["Message"])){
	   			$line = $data["Message"];
	   			if ($data["Include_timestamp"] == "true"){
	   				$line .= " ".time();
	   			}
	   			file_put_contents($file, $line."\n", FILE_APPEND);
	   		}
	   		else {
		   		$line = "";
		   	}
	   	}
	   	else {
	   		$line = "";
	   	}
        return $this->render('E02Bundle::form.html.twig', array(
	        'form' => $form->createView(),
	        'line' => $line
	    ));
    }
}
