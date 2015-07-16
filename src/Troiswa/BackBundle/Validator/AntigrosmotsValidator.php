<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntigrosmotsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(preg_match("#\b(merde|con)\b#ui", $value))
        {
            $this->context->buildViolation($constraint->message)
                            ->addViolation();
        }
    }
}