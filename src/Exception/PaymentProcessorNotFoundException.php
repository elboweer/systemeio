<?php

declare(strict_types=1);

namespace App\Exception;

final class PaymentProcessorNotFoundException extends ApiException
{
    public function __construct(string $processorName)
    {
        parent::__construct(sprintf('Payment processor "%s" not found', $processorName));
    }
}
