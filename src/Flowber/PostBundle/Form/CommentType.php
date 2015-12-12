<?php

namespace Flowber\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('message',    'textarea',           
                array(
                'attr' => array(
                     'placeholder' => 'Ecrire un message',
                                     'class' => 'group-blog-new-post-new-comment'),
                    'label' => false,
                    ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\PostBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_postbundle_comment';
    }
}
