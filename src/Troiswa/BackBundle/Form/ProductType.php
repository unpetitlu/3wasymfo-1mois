<?php

namespace Troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Entity\CategoryRepository;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('quantity')
            ->add('active', 'choice', [
                'choices' => ['non', 'oui'],
                'expanded' => true,
                'choice_value' => function($valueChoice) // Permet de choquer
                {
                    return $valueChoice ? 'true' : 'false';
                }
            ])
            ->add('category', 'entity', array(
                'class' => 'TroiswaBackBundle:Category',
                //'property' => 'title', // Pas besoin de __toString() dans l'entity Category
                'query_builder' => function (CategoryRepository $er) {
                    return $er->findTitleOrderPositionForForm();
                },
                'required' => false
            ))
            ->add('image', new ImageType());
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_product';
    }
}
