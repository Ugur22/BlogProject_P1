<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 10/2/16
 * Time: 10:42 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

///**
// * @Security("is_granted('ROLE_ADMIN')")
// *
// */
class AdminController extends Controller
{
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