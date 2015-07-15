<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntigrosmotsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $blacklists = ['merde'];

        if (in_array($value, $blacklists))
        {
            $this->context->addViolation($constraint->message);
        }
    }
}