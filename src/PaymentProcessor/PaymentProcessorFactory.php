<?php

declare(strict_types=1);

namespace App\PaymentProcessor;

use App\Exception\PaymentProcessorNotFoundException;
use App\PaymentProcessor\Adapter\PaypalAdapter;
use App\PaymentProcessor\Adapter\StripeAdapter;

readonly class PaymentProcessorFactory
{
    public function __construct(
        private PaypalAdapter $paypal,
        private StripeAdapter $stripe,
    )
    {
    }

    public function create(string $name): PaymentProcessorInterface
    {
        return match ($name) {
            'paypal' => $this->paypal,
            'stripe' => $this->stripe,
            default => throw new PaymentProcessorNotFoundException($name),
        };
    }
}
