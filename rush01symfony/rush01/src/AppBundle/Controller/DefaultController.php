<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $dom = "<h1>INTRA PAS NET (pour baptiste)</h1>";
        $dom .= $this->createMenu(array("login"));
        return new Response($dom);
    }

    /**
     * @Route("/setpassword")
     */
    public function setpasswordAction(Request $request)
    {
        if ($this->getUser() == null){
            return new RedirectResponse('/');
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createFormBuilder($user)
            ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array(
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'autocomplete' => 'new-password',
                ),
            ),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usermdp = $form->getData();

            $this->hashPassword($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new RedirectResponse('/profile');
        }

        // replace this example code with whatever you need
        return $this->render('setpassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function pageNotFoundAction()
    {
        return new RedirectResponse('/');
    }

    public function hashPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
        $user->setSalt($salt);
        $encoder = $this->container->get('security.password_encoder');
        $hashedPassword = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }

    public function createMenu($notMenu = null){
        $menu_item = array("profile", "inbox", "login", "admin", "logout");
        if ($this->getUser() == null){
            $menu_item = array("login");
        }
        if ($notMenu){
            foreach ($notMenu as $key => $value) {
                if (in_array($value, $menu_item)){
                    $tmpkey = array_search($value, $menu_item);
                    if ($tmpkey){
                        unset($menu_item[$tmpkey]);
                    }
                }
            }
        }
        $dom = "";
        $dom .= "<ul>";
        foreach ($menu_item as $key => $value) {
            $dom .= "<li><a href='/".$value."'>".$value."<a></li>";
        }
        $dom .= "</ul>";
        return $dom;
    }
}
