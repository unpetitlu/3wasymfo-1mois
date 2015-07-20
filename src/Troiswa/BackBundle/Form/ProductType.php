<?php

namespace Troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Entity\CategoryRepository;
use Troiswa\BackBundle\Form\DataTransformer\TextToTagTransformer;

class ProductType extends AbstractType
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $transformer = new TextToTagTransformer($this->em);

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
            $builder->add(
                $builder->create('tag', 'collection', [
                    'type' => new TagType(),
                    'allow_add'    => true,
                    'allow_delete' => true
                ])
                    ->addModelTransformer($transformer)
            );
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
