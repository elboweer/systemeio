<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class TaxNumber extends Constraint
{
    public string $message = 'Invalid tax number format "{{ value }}"';
}
