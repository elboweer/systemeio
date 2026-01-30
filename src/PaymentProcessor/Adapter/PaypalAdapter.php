<?php

declare(strict_types=1);

namespace App\PaymentProcessor\Adapter;

use App\Exception\PaymentException;
use App\PaymentProcessor\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Throwable;

readonly class PaypalAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $processor,
    )
    {
    }

    public function process(float $price): void
    {
        // переводим в центы
        $priceInCents = (int)($price * 100);

        try {
            $this->processor->pay($priceInCents);
        } catch (Throwable $e) {
            throw new PaymentException($e->getMessage());
        }
    }
}
