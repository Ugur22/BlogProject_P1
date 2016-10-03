<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 10/3/16
 * Time: 12:23 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Add comment', 'required' => true),
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment',
        ));

    }
}