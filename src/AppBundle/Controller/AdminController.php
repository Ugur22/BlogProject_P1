<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 10/2/16
 * Time: 10:42 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


///**
// * @Security("is_granted('ROLE_ADMIN')")
// *
// */
class AdminController extends Controller
{
    /**
     * @Route("/detailuser/{user_id}" ,name="detailuser")
     */

    public function detailUserAction(Request $request, $user_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('login');
        }
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($user_id);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $form["firstname"]->getData();
            $surname = $form["surname"]->getData();
            $username = $form["username"]->getData();
            $email = $form["email"]->getData();
            $roles = $form["roles"]->getData();

            $user->setFirstname($firstname);
            $user->setSurname($surname);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setRoles($roles);
            $em->flush();

            return $this->redirectToRoute('home');

        }
        return $this->render('admin/detailUser.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));

    }

    /**
     * @Route("/admin", name="admin_page")
     */
    public function overviewAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine();
        $user = $em->getRepository('AppBundle:User')
            ->findBy(array(), array('firstname' => 'ASC'));

        return $this->render('admin/overview.html.twig', array(
            'user' => $user
        ));

    }

    /**
     * @Route("/admin/allblogs", name="admin_allblogs")
     */
    public function allBlogsAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine();
        $blog = $em->getRepository('AppBundle:Blog')
            ->findBy(array(), array('title' => 'ASC'));

        return $this->render(':admin:allBlogs.html.twig', array(
            'blog' => $blog
        ));


    }

}