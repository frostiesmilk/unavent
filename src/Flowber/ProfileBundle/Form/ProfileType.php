<?php

namespace Flowber\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Flowber\GalleryBundle\Form\PhotoType;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subtitle',           'text')
            ->add('description',        'textarea')
            ->add('job',                'text')
            ->add('hobbies', 'collection', array('type'         => new HobbyType(),
                                             'allow_add'    => true,
                                             'allow_delete' => true))           
//          ->add('profilePicture')
            ->add('coverPicture',       new PhotoType(), array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\ProfileBundle\Entity\Profile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_profilebundle_profile';
    }
}
