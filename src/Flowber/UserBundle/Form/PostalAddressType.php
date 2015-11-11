<?php

namespace Flowber\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostalAddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',        'text', array(
                'label' => 'Adresse postale',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('zipcode',        'text', array(
                'label' => 'Code postal',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('city',           'text', array(
                'label' => 'Ville',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('country',        'text', array(
                'label' => 'Pays',
                'attr' => array(
                    'value' => 'France',
                    'class' => 'form-control'
                )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\UserBundle\Entity\PostalAddress'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_userbundle_postaladdress';
    }
}
