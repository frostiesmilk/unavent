<?php

namespace Flowber\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',      'text')
            ->add('surname',        'text')
            ->add('sex',          'choice', array(
                     'choices' => array('m' => 'Homme', 'f' => 'Femme', 'a' => 'Autre'),
                     'expanded' => true,
                     'multiple' => false
             ))
            ->add('birthdate',      'birthday', array(
                'invalid_message' => 'Date de naissance invalide',
            ))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_userbundle_user';
    }
}
