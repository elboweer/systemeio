<?php

declare(strict_types=1);

namespace App\Request;

use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

final class CalculatePriceRequest
{
    #[Assert\Sequentially([
        new Assert\NotBlank(message: 'Product ID is required'),
        new Assert\Positive(message: 'Product ID must be >= 0'),
    ])]
    public ?int $product = null;

    #[Assert\Sequentially([
        new Assert\NotBlank(message: 'Tax number is required'),
        new TaxNumber(),
    ])]
    public ?string $taxNumber = null;

    public ?string $couponCode = null;
}
