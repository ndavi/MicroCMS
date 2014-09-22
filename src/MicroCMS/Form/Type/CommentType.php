<?php

namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'rows' => '4',
                    'class' => 'form-control',
                    'placeholder' => 'Enter your comment',
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'Publish comment',
                'attr' => array('class' => 'btn btn-primary'),
            ));
    }

    public function getName()
    {
        return 'comment';
    }
}
