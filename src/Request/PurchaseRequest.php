<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class PurchaseRequest
{
    use CommonProductRequestTrait;

    #[Assert\Sequentially([
        new Assert\NotBlank(message: 'Payment processor is required'),
        new Assert\Choice(choices: ['paypal', 'stripe'], message: 'Invalid payment processor'),
    ])]
    public ?string $paymentProcessor = null;
}
