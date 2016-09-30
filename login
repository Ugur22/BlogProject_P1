<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/26/16
 * Time: 8:06 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController2 extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'email', 'required' => true),
            ))
            ->add('password', PasswordType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'password', 'required' => true),
            ))->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $data = $request->request->all();
            $email = $data['form']['email'];
            $password = $data['form']['password'];
            $login = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findOneBy(
                    array('email' => $email)
                );


            if ($login) {
                $encoder_service = $this->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($login);

                $validPassword = $encoder->isPasswordValid(
                    $login->getPassword(), // the encoded password
                    $password,       // the submitted password
                    $login->getSalt()
                );

                if ($validPassword) {
                    $session = new Session();
                    $session->set('email', $email);
                    $session->save();
                    return $this->redirectToRoute('home');
                }
            }
        }

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        $session = new Session();
        $session->clear();
        return $this->redirectToRoute('home');
    }

}