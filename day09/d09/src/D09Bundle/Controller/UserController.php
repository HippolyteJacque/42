<?php

namespace D09Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use D09Bundle\Entity\User;
use D09Bundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/login")
     */
    public function loginAction()
    {
    	$defaultData = array();
        return $this->render('D09Bundle::login.html.twig', array('form' => $form));
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

	            $encoder = $this->container->get('security.password_encoder');
				$encoded = $encoder->encodePassword($user, $user->getPassword());

				$user->setPassword($encoded);

	            $entityManager = $this->getDoctrine()->getManager();
	            $entityManager->persist($user);
	            $entityManager->flush();


	            return $this->redirectToRoute('/login');
        }

        return $this->render(
            'D09Bundle::login.html.twig',
            array('form' => $form->createView())
        );
    }
}
