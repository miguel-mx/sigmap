<?php
// src/AppBundle/Form/TalkType

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TalkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title','Symfony\Component\Form\Extension\Core\Type\TextType', array('required' => false));
        $builder->add('abstract','Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required' => false));
    }

    public function getName()
    {
        return 'app_user_talk';
    }
}
