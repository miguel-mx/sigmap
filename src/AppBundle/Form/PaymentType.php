<?php

namespace AppBundle\Form;

use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;


class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthdate', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'widget' => 'single_text',
                'html5' => false,
                'required' => false))
            ->add('rfc')
            ->add('empresa', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('required' => false))
            ->add('calle')
            ->add('numexterior')
            ->add('numinterior', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('required' => false))
            ->add('colonia')
            ->add('cpostal')
            ->add('pais', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'class'       => 'AppBundle:Country',
                'query_builder' => function (CountryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
//                'query_builder' => function (CountryRepository $er) {
//                    return $er->findAllOrderedByName();
//                },
                'placeholder' => '',
            ))
//            ->add('estado')
            ->add('delegacion')
            ->add('telefono');

//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $payment) {
//                $form = $payment->getForm();
//
//                // this would be your entity, i.e. SportMeetup
//                $data = $payment->getData();
//
//                $pais = $data->getPais();
//                $estado = null === $pais ? array() : $country->getStates();
//
//                $form->add('estado', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
//                    'class' => 'AppBundle:States',
//                    'placeholder' => '',
//                    'choices' => $estados,
//                    'choices_as_values' => true,
//                ));
//            }
//        );

        $formModifier = function (FormInterface $form, Country $country = null) {
            $estados = null === $country ? array() : $country->getStates();
            $form->add('estado', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'class' => 'AppBundle:States',
                'placeholder' => '',
                'choices' => $estados,
                'choices_as_values' => true,
            ));

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $payment) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $payment->getData();

                $formModifier($payment->getForm(), $data->getPais());
            }
        );

        $builder->get('pais')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $payment) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $pais = $payment->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($payment->getForm()->getParent(), $pais);
            }
        );

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Payment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_payment';
    }


}
