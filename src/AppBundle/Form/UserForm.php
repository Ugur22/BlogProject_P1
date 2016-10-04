<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 10/3/16
 * Time: 9:24 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'firstname', 'required' => true),
            ))
            ->add('surname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'surname', 'required' => true),
            ))
            ->add('username', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'username', 'required' => true),
            ))
            ->add('email', EmailType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'email', 'required' => true),
            ))
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                // render check-boxes
                'choices' => [
                    "admin" => 'ROLE_ADMIN',
                    "user" => 'ROLE_USER',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }

}