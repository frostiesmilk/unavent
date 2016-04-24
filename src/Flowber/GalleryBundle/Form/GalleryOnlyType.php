<?php

namespace Flowber\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Flowber\GalleryBundle\Form\PhotoOnlyType;

class GalleryOnlyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadedFiles', 'file', array(
                    'multiple' => true,
                    'data_class' => null,
                    'required' => false,
                    'attr' => array('accept' => 'image/*',
                        ),                    
                ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\GalleryBundle\Entity\Gallery'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_gallerybundle_galleryonly';
    }
}
