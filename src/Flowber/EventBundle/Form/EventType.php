<?php

namespace Flowber\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Flowber\UserBundle\Form\PostalAddressWithNameType;
use Flowber\GalleryBundle\Form\PhotoOnlyType;
use Flowber\EventBundle\Form\DataTransformer\DateTimeTransformer;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text')
            ->add('subtitle',       'text', array(
                     'required' => false
             ))
            ->add('description',    'textarea', array(
                     'required' => false
             ))
            ->add('startDate',      'date', array(
                                                    'widget' => 'single_text',
                                                    'input' => 'datetime',
                                                    'format' => 'dd/MM/yyyy',
                                                    'attr' => array('class' => 'flowber_datepicker'),
            ))
            ->add('startTime',      'time', array(
                                                    'widget' => 'single_text',
                                                    'input' => 'datetime',
                                                    'attr' => array('class' => 'flowber_timepicker'),
            ))
            ->add('endDate',      'date', array(
                                                    'widget' => 'single_text',
                                                    'input' => 'datetime',
                                                    'format' => 'dd/MM/yyyy',
                                                    'attr' => array('class' => 'flowber_datepicker'),
                                                    'required' => false
            ))
            ->add('endTime',      'time', array(
                                                    'widget' => 'single_text',
                                                    'input' => 'datetime',
                                                    'attr' => array('class' => 'flowber_timepicker'),
                                                    'required' => false
            ))
            ->add('privacy',          'choice', array(
                     'choices' => array('public' => 'Publique', 'private' => 'Privée'),
                     'expanded' => true,
                     'multiple' => false
             ))            
            ->add('category',       'text', array(
                     'required' => false
             ))
            ->add('postalAddress',  new PostalAddressWithNameType(), array('required'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\EventBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_eventbundle_event';
    }
}
