<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PositionCategory extends Constraint
{
    public $message = "La position existe déjà.";

    public function validatedBy()
    {
        // Return alias service
        return 'troiswa_validator_position_cat';
    }
}
