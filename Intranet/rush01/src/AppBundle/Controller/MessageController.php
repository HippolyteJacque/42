<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MessageController extends DefaultController
{
	/**
     * @Route("/inbox")
    */
    public function inboxAction()
    {
    	if ($this->getUser() == null)
    		return new RedirectResponse('/login');
    	$dom = '<h2>INBOX</h2>';

    	$em = $this->getDoctrine()->getManager();

    	$user = $this->getUser();
    	$user_id = $user->getId();

    	$messages = (array)$em->getRepository(Message::class)->findBy(
    		array('idTo' => $user_id),
    		array('id' => 'DESC')
    	);

		foreach ($messages as $message) {
			$content = $message->getContent();
			$from_id =  $message->getIdFrom();
			$from_user = $em->getRepository(User::class)->findOneBy(array('id' => $from_id));
			$from_name = $from_user->getUsername();
			$dom .= '<p style="font-weight: bold;">'.$content.'</p><a href="/newmessage/'.$from_id.'">répondre à '.$from_name.'</a>';
		}

		$dom .= '<br><br><br><br><h3><a href="/newmessage">nouveau message<a></h3>';

        return new Response($dom);
    }

    /**
     * @Route("/newmessage/{id}")
      * @Route("/newmessage/")
    */
    public function newmessageAction(Request $request, $id = 0)
    {
    	$message = new Message();
    	if ($id == 0){
    		$em = $this->getDoctrine()->getManager();
    		$users = (array)$em->getRepository(User::class)->findAll();
    		$max_id = count($users);
    		$form = $this->createFormBuilder($message)
    		->add('idTo', IntegerType::class, array('attr' => array('min' => 1, 'max' => $max_id)))
        	->add('content', TextType::class)
        	->add('envoyer', SubmitType::class)
        	->getForm();
    	}
    	else {
    		$form = $this->createFormBuilder($message)
        	->add('content', TextType::class)
        	->add('envoyer', SubmitType::class)
        	->getForm();
    	}

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$user = $this->getUser();
    		$user_id = $user->getId();

            $message_content = $form->getData();

            $message->setIdFrom($user_id);
            if ($id != 0){
            	$message->setIdTo($id);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return new RedirectResponse('/inbox');
        }

        return $this->render('setpassword.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

?>