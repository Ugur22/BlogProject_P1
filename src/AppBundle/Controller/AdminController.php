<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 10/2/16
 * Time: 10:42 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryForm;
use AppBundle\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


///**
// * @Security("is_granted('ROLE_ADMIN')")
// *
// */
class AdminController extends Controller
{

    /**
     * @Route("/admin/delete/{user_id}" ,name="deleteUser")
     */
    public function deleteAction($user_id)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($user_id);
        $em->remove($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', "User deleted");
        return $this->redirectToRoute('admin_page');

    }

    /**
     * @Route("/admin/blog/delete//{blog_id}" ,name="deleteBlog")
     */
    public function deleteCategoryAction($blog_id)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('AppBundle:Blog')
            ->find($blog_id);
        $em->remove($blog);
        $em->flush();
        return $this->redirectToRoute('admin_allblogs');

    }

    /**
     * @Route("/admin/addCategory" ,name="addCategory")
     */
    public function addCategory(Request $request)
    {


        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('overviewcat');
        }

        return $this->render(':admin:addCategory.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/admin/detailuser/{user_id}" ,name="detailuser")
     */

    public function detailUserAction(Request $request, $user_id)
    {

        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($user_id);
        $userRoles = $em->getRepository('AppBundle:User')
            ->findAll();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = $form["firstname"]->getData();
            $surname = $form["surname"]->getData();
            $username = $form["username"]->getData();
            $email = $form["email"]->getData();
            $roles = $form["roles"]->getData();

            $user->setUsername($firstname);
            $user->setSurname($surname);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setRoles($roles);
            $em->flush();

            return $this->redirectToRoute('admin_page');

        }
        return $this->render('admin/detailUser.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'userroles' => $userRoles,
        ));

    }


    /**
     * @Route("/admin", name="admin_page")
     */
    public function overviewAction()
    {

        $em = $this->getDoctrine();
        $user = $em->getRepository('AppBundle:User')
            ->findBy(array(), array('firstname' => 'ASC'));

        return $this->render('admin/overview.html.twig', array(
            'user' => $user
        ));

    }

    /**
     * @Route("/admin/allblogs", name="admin_allblogs")
     */
    public function allBlogsAction()
    {


        $em = $this->getDoctrine();
        $blog = $em->getRepository('AppBundle:Blog')
            ->findBy(array(), array('title' => 'ASC'));

        return $this->render(':admin:allBlogs.html.twig', array(
            'blog' => $blog
        ));
    }


    /**
     * @Route("/admin/overviewCategory", name="overviewcat")
     */
    public function overviewcatAction()
    {


        $em = $this->getDoctrine();
        $category = $em->getRepository('AppBundle:Category')
            ->findBy(array(), array('name' => 'ASC'));

        return $this->render(':admin:overviewCategory.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * @Route("/admin/blogOff/{blog_id}", name="blogOff")
     */
    public function blogOffAction($blog_id)
    {


        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('AppBundle:Blog')
            ->find($blog_id);

        $blog->setActive(false);
        $em->flush();
        return $this->redirectToRoute('admin_allblogs');

    }

    /**
     * @Route("/admin/blogOn/{blog_id}", name="blogOn")
     */
    public function blogOnAction($blog_id)
    {

        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('AppBundle:Blog')
            ->find($blog_id);

        $blog->setActive(true);
        $em->flush();
        return $this->redirectToRoute('admin_allblogs');

    }


}