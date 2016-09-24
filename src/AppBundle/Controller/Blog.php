<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/22/16
 * Time: 12:40 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class Blog extends Controller
{
    /**
     * @Route("/blog/{blogName}")
     */

    public function showAction($blogName)
    {
        $templating = $this->container->get('templating');
        $html = $templating->render('blog/show.html.twig', [
            'name' => $blogName
        ]);


        return new Response($html);

    }
}