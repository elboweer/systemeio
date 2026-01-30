<?php

declare(strict_types=1);

namespace App\PaymentProcessor;

interface PaymentProcessorInterface
{
    public function process(float $price): void;
}
