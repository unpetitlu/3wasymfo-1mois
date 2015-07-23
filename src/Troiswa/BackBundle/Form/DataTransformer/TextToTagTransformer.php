<?php

namespace Troiswa\BackBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;


class TextToTagTransformer implements DataTransformerInterface
{
    /**
    * @var ObjectManager
    */
    private $om;

    /**
    * @param ObjectManager $om
    */
    public function __construct($om)
    {
        $this->om = $om;
    }

    /**
    * Transforms an object (issue) to a string (number).
    *
    * @param  Issue|null $issue
    * @return string
    */
    public function transform($tags)
    {
        return $tags;

    }

    /**
    * Transforms a string (number) to an object (issue).
    *
    * @param  string $number
    * @return Issue|null
    * @throws TransformationFailedException if object (issue) is not found.
    */
    public function reverseTransform($tags)
    {
        $finalTag = [];
        if (!$tags) {
            return null;
        }

        foreach($tags as $onetag)
        {
            // Vérification de son existence dans la table tag
            $tag = $this->om
                ->getRepository('TroiswaBackBundle:Tag')
                ->findOneBy(array('word' => $onetag->getWord()))
            ;

            if ($tag)
            {
                array_push($finalTag, $tag);
            }
            else
            {
                array_push($finalTag, $onetag);
            }

        }

        return $finalTag;
    }
}