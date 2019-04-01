<?php

namespace E03Bundle\Controller;

use E01Bundle\Entity\users;
use E01Bundle\Form\PostType;
use E01Bundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class PostController extends Controller
{
    /**
     * @Route("/post", name="post")
     */
    public function post(Request $request)
    {
        // 1) build the form
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


			// 3) Encode the password (you could also do this via Doctrine listener)
            $user = $this->get('security.token_storage')->getToken()->getUser();
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $post->setCreated(new \DateTime('now'));
            $post->setAuthor($user->getUsername());
            $entityManager->persist($post);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'E03Bundle::post.html.twig',
            array('form' => $form->createView())
        );
    }
}
?>