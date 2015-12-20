<?php

namespace Flowber\PrivateMessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrivateMessageWinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject',        'hidden')
            ->add('message',        'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\PrivateMessageBundle\Entity\PrivateMessage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_privatemessagebundle_privatemessage';
    }
}
