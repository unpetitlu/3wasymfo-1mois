<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositionCategoryValidator extends ConstraintValidator
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $position = $this->em->getRepository('TroiswaBackBundle:Category')
                            ->findOneByPosition($value);

        if($position)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}