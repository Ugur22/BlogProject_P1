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
use AppBundle\Entity\User_like;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine();
        $blog = $em->getRepository('AppBundle:Blog')
            ->findAll();


        $category = $em->getRepository('AppBundle:Category')
            ->findAll();

        $blogAmount = count($blog);


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $blogAmount = 5;
        }


        $blogs = $em->getRepository('AppBundle:Blog')
            ->findBy(array('active' => true), array('date' => 'DESC'), $blogAmount, 0);
        return $this->render('home/index.html.twig', [
            'blogs' => $blogs,
            'category' => $category
        ]);

    }


    /**
     * @Route("/addComment/{blog_Id}", name="addComment",options={"expose"=true})
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
        $data = $request->request->get('data');
        $date = new \DateTime();
        $date->format('Y-m-d H:i');
        $comment->setUser($user);
        $comment->setDate($date);
        $comment->setBlog($blog);
        $comment->setText($data);
        if ($request->isMethod('POST')) {
            if (!empty($data)) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
            }
        }
        $comments = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')->getBlogById($blog_Id);

        return new JsonResponse($comments);
    }

    /**
     * @Route("/addlike/{blog_Id}", name="addlike")
     */
    public function addLikesAction($blog_Id)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $user = $this->getUser();
        $userId = $accessor->getValue($user, 'id');
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);
        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->find($blog_Id);


        $checkduplicate = $this->getDoctrine()
            ->getRepository('AppBundle:User_like')
            ->findUserLike($blog_Id, $userId);


        $like = $this->getDoctrine()
            ->getRepository('AppBundle:User_like')->findUserLikeId($blog_Id, $userId);

        $countLikes = $this->getDoctrine()
            ->getRepository('AppBundle:User_like')->countLikesPostAjax($blog_Id);


        if (!$checkduplicate) {
            $user_like = new User_like();
            $user_like->setUser($user);
            $user_like->setBlog($blog);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_like);
            $em->flush();
        } else {
            $likeId = $like[0]['id'];
            $likeID = $this->getDoctrine()
                ->getRepository('AppBundle:User_like')->find($likeId);
            $em = $this->getDoctrine()->getManager();
            $em->remove($likeID);
            $em->flush();
        }

        return new JsonResponse($countLikes);
    }

}