<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/22/16
 * Time: 2:55 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $templating = $this->container->get('templating');
        $html = $templating->render('home/index.html.twig', [

        ]);

        return new Response($html);

    }

}