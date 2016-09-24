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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * @Route("/blog")
     */

    public function showAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find(2);
        $blog = new Blog();
//        $blog->setImg('funny.png');
        $blog->setDate(new \DateTime());
        $blog->setUser($user);

        $form = $this->createFormBuilder($blog)
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'choose a title'),
            ))
            ->add('text', TextareaType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'write a story'),
            ))
            ->add('img', FileType::class, array(
                'attr' => array('class' => 'fileinput-new'),
            ))
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Blogpost', 'attr' => array('class' => 'btn btn-default')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $blog->getImg();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $blog->setImg($fileName);

            $blog = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('blog/show.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}