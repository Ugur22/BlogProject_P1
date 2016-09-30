<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user/new", name="user_registration")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $user->setRole("user");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "User Created.");

            return $this->redirectToRoute('home');


        }
        return $this->render(
            ':user:new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/user/{userId}",name="myblog_page")
     */
    public function MyblogsAction($userId)
    {

        $em = $this->getDoctrine();
        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array('user' => $userId), array('date' => 'DESC'));

        return $this->render(':user:myblogs.html.twig', [
            'userId' => $userId,
            'blogs' => $blogs
        ]);
    }

}