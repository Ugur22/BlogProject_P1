<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/30/16
 * Time: 7:09 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{categoryId}",name="category_page")
     */

    public function showAction($categoryId)
    {
        $em = $this->getDoctrine();
        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array(), array('date' => 'DESC'));

        return $this->render(':category:show.html.twig', [
            'category' => $categoryId,
            'blogs' => $blogs

        ]);

    }
}