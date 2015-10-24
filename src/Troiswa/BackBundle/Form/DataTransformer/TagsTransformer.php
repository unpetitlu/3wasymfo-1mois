<?php

namespace Troiswa\BackBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Tag;

class TagsTransformer implements DataTransformerInterface
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function transform($tags)
    {
        if (!is_null($tags)) {
            return join('|', $tags->toArray());
        }

        return;
    }


    public function reverseTransform($tags)
    {
        if (is_null($tags) || !$tags){
            return;
        }

        $allTags = new ArrayCollection();

        $tags = explode('|', $tags);
        foreach($tags as $t)
        {
            $newTag = $this->objectManager->getRepository('TroiswaBackBundle:Tag')->findOneBy(['word' => $t]);
            if (!$newTag)
            {
                $newTag = new Tag();
                $newTag->setWord($t);
            }
            $allTags->add($newTag);
        }

        return $allTags;
    }
}