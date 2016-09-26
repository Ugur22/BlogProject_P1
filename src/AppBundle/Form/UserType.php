<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/26/16
 * Time: 1:10 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
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
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password',
                    'attr' => array(
                        'placeholder' => 'Password', 'class' => 'form-control',
                    ),),
                'second_options' => array('label' => 'Repeat Password', 'attr' => array(
                    'placeholder' => 'retype password', 'class' => 'form-control',
                ),),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => ['Default', 'Registration']
        ));
    }
}