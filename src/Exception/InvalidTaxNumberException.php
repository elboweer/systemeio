<?php

declare(strict_types=1);

namespace App\Exception;

final class InvalidTaxNumberException extends ApiException
{
    public function __construct(string $taxNumber)
    {
        parent::__construct(sprintf('Invalid tax number format: "%s"', $taxNumber));
    }
}
