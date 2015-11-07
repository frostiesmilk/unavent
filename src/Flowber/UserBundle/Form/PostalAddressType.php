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
            ->add('address',        'text')
            ->add('zipcode',        'text')
            ->add('city',           'text')
            ->add('country',        'text')
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
