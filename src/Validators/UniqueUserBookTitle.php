<?php

namespace App\Validators;

use Symfony\Component\Validator\Constraint;
use Attribute;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class UniqueUserBookTitle extends Constraint
{
    public string $message = 'You have already created a book with this title.';
}
