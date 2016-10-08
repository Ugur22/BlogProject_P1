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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine();

        $blog = $em->getRepository('AppBundle:Blog')
            ->findAll();

        $blogAmount = count($blog);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $blogAmount = 5;
        }


        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array('active' => true), array('date' => 'DESC'), $blogAmount, 0);


        return $this->render('home/index.html.twig', [
            'blogs' => $blogs
        ]);

    }

    /**
     * @Route("/addComment/{blog_Id}", name="addComment")
     */
    public function addCommentAction($blog_Id, Request $request)
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
            ->find($blog_Id);

        $postData = $request->request->all();
        $data = $postData['commentText'];
        $comment->setUser($user);
        $comment->setDate(new \DateTime());
        $comment->setBlog($blog);
        $comment->setText($data);

        $em = $this->getDoctrine()->getManager();

        $em->persist($comment);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/addlike/{blog_Id}", name="addlike")
     */
    public function addLikesAction($blog_Id)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('AppBundle:Blog')
            ->findOneBy(['id' => $blog_Id]);

        $like = $blog->getLikes();
        $blog->setLikes($like + 1);
        $em->flush();

        return $this->redirectToRoute('home');

    }

}