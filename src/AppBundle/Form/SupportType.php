<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Registration fee' => 'Registration fee',
                    'Accommodation and registration fee' => 'Accommodation and registration fee',
                ),
                'choices_as_values' => true,
            ))
            ->add('studies', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Undergraduate' => 'Undergraduate',
                    'Masters' => 'Masters',
                    'PhD' => 'PhD',
                ),
                'choices_as_values' => true,
            ))
            ->add('reasons','Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('prof1')
            ->add('mailprof1')
            ->add('prof2')
            ->add('mailprof2')

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Support'
        ));
    }
}
