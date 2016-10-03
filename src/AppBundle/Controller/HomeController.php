<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/22/16
 * Time: 2:55 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Blog;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $comment = new Comment();
        $accessor = PropertyAccess::createPropertyAccessor();


        $user = $this->getUser();
        $userId = $accessor->getValue($user, 'id');

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->find(array('id' => 21));


        $comment->setUser($user);
        $comment->setDate(new \DateTime());
        $comment->setBlog($blog);

        $form = $this->createForm(CommentForm::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('home');
        }

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
            'blogs' => $blogs,
            'form' => $form->createView()
        ]);


    }

}