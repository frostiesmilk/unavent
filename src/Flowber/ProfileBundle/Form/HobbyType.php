<?php

namespace Flowber\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class HobbyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity', array(
                    'label'  => 'Catégorie',
                    'class'    => 'FlowberFrontOfficeBundle:Category',
                    'property' => 'title',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.title', 'ASC');
                    },
                    )
                )
            ->add('percent', null,  
                    array(
                        'label'  => 'Pourcentage',
                    )
                )
            ->add('description', 'text', 
                    array(
                        'label'  => 'Description',
                    )  
                )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\ProfileBundle\Entity\Hobby'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_profilebundle_hobby';
    }
}
