<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StrongPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(strlen($value) < $constraint->min)
        {
            $this->context->buildViolation($constraint->messageMin)
                ->setParameter('{{ limit }}', $constraint->min)
                ->addViolation();
        }
        elseif($constraint->caractere && !preg_match("#[".$constraint->caractere."]#", $value))
        {
            $this->context->buildViolation($constraint->messageCaractere)
                ->setParameter('{{ valid }}', $constraint->caractere)
                ->addViolation();
        }

    }
}