<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/22/16
 * Time: 2:55 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();
        $blog = $em->getRepository('AppBundle:Blog')
            ->findAll();
        $blogAmount = count($blog);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $blogAmount = 5;
        }


        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array(), array('date' => 'DESC'), $blogAmount, 0);


        return $this->render('home/index.html.twig', [
            'blogs' => $blogs
        ]);


    }

}