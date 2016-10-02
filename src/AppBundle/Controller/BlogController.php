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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;


class BlogController extends Controller
{

    /**
     * @Route("/blog/detail/{blog_id}", name="blog_detail")
     */
    public function detailAction($blog_id)
    {
        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBy(['id' => $blog_id]);


        if (!$blog) {
            throw  $this->createNotFoundException(
                'No blog found for id ' . $blog_id
            );
        }


        return $this->render('blog/detail.html.twig', [
            'blog' => $blog

        ]);
    }

    /**
     * @Route("/blog", name="create_blog")
     */

    public function showAction(Request $request)
    {
        $accessor = PropertyAccess::createPropertyAccessor();


        $user = $this->getUser();
        $userId = $accessor->getValue($user, 'id');

        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($userId);


        $blog = new Blog();
        $blog->setDate(new \DateTime());
        $blog->setUser($user);


        $form = $this->createFormBuilder($blog)
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'choose a title', 'required' => true),
            ))
            ->add('text', TextareaType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'write a story', 'required' => true),
            ))
            ->add('img', FileType::class, array(
                'attr' => array('class' => 'fileinput-new', 'required' => true),
            ))
            ->add('categories', EntityType::class, array(
                'attr' => array('required' => true),
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'multiple' => TRUE,
                'expanded' => TRUE
            ))->getForm();
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

            return $this->redirectToRoute('home');
        }

        return $this->render('blog/show.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}