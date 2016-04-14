<?php

namespace Flowber\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostalAddressWithNameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',           'text')            
            ->add('address',        'text')
            ->add('city',           'text')
            ->add('zipcode',        'text')
            ->add('country',        'text', array('data' => 'France'))
            ->add('coordinates',     'hidden')
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
