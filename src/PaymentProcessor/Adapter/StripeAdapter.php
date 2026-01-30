<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Adapter;

use App\Exception\PaymentException;
use App\PaymentProcessor\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;
use Throwable;

readonly class StripeAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private StripePaymentProcessor $processor,
    )
    {
    }

    public function process(float $price): void
    {
        try {
            $success = $this->processor->processPayment($price);
        } catch (Throwable $e) {
            // на всякий
            throw new PaymentException($e->getMessage());
        }

        if (!$success) {
            throw new PaymentException('Stripe payment failed');
        }
    }
}
