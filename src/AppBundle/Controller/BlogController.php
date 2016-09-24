<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/22/16
 * Time: 12:40 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends Controller
{

    /**
     * @Route("/blog/new")
     */
    public function newAction()
    {

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find(2);
        $blog = new Blog();
        $blog->setTitle('my first blogpost');
        $blog->setDate(new \DateTime());
        $blog->setText('OMG I finally became a blogger');
        $blog->setImg('blog.jpg');
        $blog->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($blog);
        $em->flush();

        return new Response('<html><body>Blogpost created</body></html>');
    }

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