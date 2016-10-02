<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $role = ["ROLE_USER"];
        $user->setRoles($role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "User Created.");

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );


        }
        return $this->render(
            'user/new.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * @Route("/users/{userId}",name="myblog_page")
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


    /**
     * @Route("/myblog",name="userBlog_page")
     */
    public function UserBlogAction()
    {


        $accessor = PropertyAccess::createPropertyAccessor();
        $user = $this->getUser();
        $userId = $accessor->getValue($user, 'id');


        $em = $this->getDoctrine();
        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array('user' => $userId), array('date' => 'DESC'));

        return $this->render(':user:UserBlog.html.twig', [
            'blogs' => $blogs
        ]);
    }

}