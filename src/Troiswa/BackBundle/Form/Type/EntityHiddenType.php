<?php

namespace Troiswa\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Form\DataTransformer\EntityToIdTransformer;

class EntityHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdTransformer($this->objectManager, $options['class']);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['class']) // Oblige à avoir la propriété class lorsqu'on utilisera entity_hidden dans un formulaire
            //->setDefault('property', 'bidule') // Paramètre optionnel
            //->setDefaults([]) //Identique à la méthode au dessus mais il faut envoyer un tableau
            ->setDefaults([
                'invalid_message' => 'The entity does not exist.',
            ])
        ;
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'entity_hidden';
    }
}