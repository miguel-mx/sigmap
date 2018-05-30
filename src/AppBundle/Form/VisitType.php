<?php
// src/AppBundle/Form/TalkType

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dinner','Symfony\Component\Form\Extension\Core\Type\IntegerType', array(
            'attr' => array(
                'min' => 0,
                'max' => 5
            ),
            'required' => false
        ));
        $builder->add('morelia','Symfony\Component\Form\Extension\Core\Type\IntegerType', array(
            'attr' => array(
                'min' => 0,
                'max' => 5
            ),
            'required' => false
        ));
        $builder->add('patzcuaro','Symfony\Component\Form\Extension\Core\Type\IntegerType', array(
            'attr' => array(
                'min' => 0,
                'max' => 5
            ),
            'required' => false
        ));

    }

    public function getName()
    {
        return 'app_user_visit';
    }
}
