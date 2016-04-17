<?php

namespace Flowber\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Flowber\GalleryBundle\Form\GalleryOnlyType;
use Flowber\GalleryBundle\Form\DataTransformer\PictureFilesTransformer;

class PostWithPicturesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'textarea', array(
                                                'attr' => array(
                                                    'placeholder' => 'Ecrire un message'),
                                                    'label' => false,
                                                 ))
                ->add("gallery", new GalleryOnlyType(), array('required'=>false));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\PostBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_postbundle_postwithpictures';
    }
}
