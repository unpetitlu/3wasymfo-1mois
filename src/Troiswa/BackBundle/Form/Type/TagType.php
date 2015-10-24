<?php

namespace Troiswa\BackBundle\Form\Type;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Form\DataTransformer\TagsTransformer;

class TagType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    private $class;

    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagsTransformer($this->objectManager);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $entities = $this->objectManager->getRepository($this->class)->findAll();
        $words = array_map(function($item){ return $item->getWord(); }, $entities);

        $resolver->setDefaults([
            'attr' => [
                'class' => 'tag-input',
                'data-value' => implode('|',$words)
            ]
        ]);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tags';
    }
}