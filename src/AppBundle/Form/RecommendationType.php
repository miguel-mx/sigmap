<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RecommendationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('relationships', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
                'label'=>'*Relationship',
                'choices'=>array(
                    'Coauthor'=>'Coauthor',
                    'Thesis supervisor'=>'Thesis supervisor',
                    'Former thesis supervisor'=>'Former thesis supervisor',
                    'Other'=>'Other'
                ),
                // *this line is important*
                'choices_as_values' => true,
                'placeholder' => 'Select',
                'label'=>'*Relationship',
                'mapped'=> false,
            ))
            ->add('relationship','Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Other relationship type',
                'read_only'=> true
            ))
            ->add('description','Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('skills','Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('rank','Symfony\Component\Form\Extension\Core\Type\IntegerType', array('attr' => array('min' => 1, 'max' => 5)))
            ->add('comments','Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
                'required'   => false
            ));

//        $formModifier = function (FormInterface $form, $other) {
//            if ( 'Other' == $other) {
//                $form->add('relationship','Symfony\Component\Form\Extension\Core\Type\TextType', array(
//                    'label' => 'Other relationship type'
//                ));
//            }
//        };
//
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                // this would be your entity, i.e. SportMeetup
//                $data = $event->getData();
//                $formModifier($event->getForm(), $data->getRelationship());
//            }
//        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $rec) {
                $data = $rec->getData();
                if (isset($data['relationships'])){
                    $val = $data['relationships'];
                    if ( $val !='Other') {
                        $data['relationship'] = $val;
                        $rec->setData($data);
                    }
                }
                else {
                    $data['relationship']='';
                }
            }
        );

//        $builder->get('relationships')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier) {
//                // It's important here to fetch $event->getForm()->getData(), as
//                // $event->getData() will get you the client data (that is, the ID)
//                $sport = $event->getForm()->getData();
//                // since we've added the listener to the child, we'll have to pass on
//                // the parent to the callback functions!
//                $formModifier($event->getForm()->getParent(),$sport);
//            }
//        );

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Recommendation'
        ));
    }
}
