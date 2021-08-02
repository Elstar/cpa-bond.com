<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueCategory extends Constraint
{
    public string $message = 'Category already exist';
}
