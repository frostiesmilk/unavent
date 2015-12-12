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
 * Description of ResetPasswordFormType
 *
 * @author Equina
 */
class RegistrationFormType extends AbstractType{
    //put your code here
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('save', 'submit')
//        ;
    }
    
    public function getParent()
    {
        return 'fos_user_resetting';
    }
    
    public function getName()
    {
        return 'flowber_user_resetting';
    }
}
