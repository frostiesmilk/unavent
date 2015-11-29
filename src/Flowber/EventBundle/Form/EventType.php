<?php

namespace Flowber\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Flowber\UserBundle\Form\PostalAddressWithNameType;
use Flowber\GalleryBundle\Form\PhotoOnlyType;

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
            ->add('eventDate',      'datetime')
            ->add('privacy',          'choice', array(
                     'choices' => array('Publique' => 'public', 'Privée' => 'private'),
                     'expanded' => true,
                     'multiple' => false
             ))            
            ->add('category',       'text', array(
                     'required' => false
             ))
            ->add('postalAddress',  new PostalAddressWithNameType())
            ->add('profilePicture',  new PhotoOnlyType(), array(
                     'required' => false
             ))
            ->add('coverPicture',  new PhotoOnlyType(), array(
                     'required' => false
             ))
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
