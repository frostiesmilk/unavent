<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of RegistrationFormType
 *
 * @author Equina
 */
class RegistrationFormType extends AbstractType{
    //put your code here
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->add('firstname',      'text', array(
                'attr' => array(
                    'placeholder' => 'Prénom',
                ))
            )
            ->add('surname',        'text', array(
                'attr' => array(
                    'placeholder' => 'Nom',
                ))
            )
            ->add('birthdate',      'birthday', array(
                'invalid_message' => 'Date de naissance invalide',
            ))
            ->add('sex',          'choice', array(
                     'choices' => array('m' => 'Homme', 'f' => 'Femme', 'a' => 'Autre'),
                     'expanded' => true,
                     'multiple' => false
             ))
            ->add('save', 'submit')
        ;
    }
    
    public function getParent()
    {
        return 'fos_user_registration';
    }
    
    public function getName()
    {
        return 'flowber_user_registration';
    }
}
