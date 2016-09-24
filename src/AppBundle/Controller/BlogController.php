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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/blog")
     */

    public function showAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find(2);
        $blog = new Blog();
        $blog->setTitle('write a blog post');
        $blog->setText('helloo');
        $blog->setImg('funny.png');
        $blog->setDate(new \DateTime());
        $blog->setUser($user);

        $form = $this->createFormBuilder($blog)
            ->add('title', TextType::class)
            ->add('text', TextType::class)
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Blogpost'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();
        }

        return $this->render('blog/show.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}