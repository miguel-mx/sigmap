<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('surname');
        $builder->add('gender','Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
            'choices'  => array(
                'Female' => 'M',
                'Male' => 'H',
            ),
            'choices_as_values' => true,
        ));
        $builder->add('citizenship');
        $builder->add('affiliation');
        $builder->add('student', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
            'choices'  => array(
                'Yes' => true,
                'No' => false,
            ),
            'choices_as_values' => true,
        ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}
