<?php

namespace Flowber\GroupBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GroupsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text')
            ->add('subtitle',       'text', array(
                    'required' => false
            ))
            ->add('description',    'textarea', array(
                    'required' => false
            ))
            ->add('categories', 'entity', array(
                    'class'    => 'FlowberFrontOfficeBundle:Category',
                    'property' => 'title',
                    'multiple' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.title', 'ASC');
                    },
                )
            )
            ->add('privacy',          'choice', array(
                    'choices' => array('public' => 'Publique', 'private' => 'Privé'),
                    'expanded' => true,
                    'multiple' => false
            ))     
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowber\GroupBundle\Entity\Groups'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowber_groupbundle_groups';
    }
}
