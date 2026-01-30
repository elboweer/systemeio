<?php

declare(strict_types=1);

namespace App\Exception;

final class PaymentException extends ApiException
{
    public function __construct(string $message = 'Payment failed')
    {
        parent::__construct($message);
    }
}
