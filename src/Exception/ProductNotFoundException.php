<?php

declare(strict_types=1);

namespace App\Exception;

final class ProductNotFoundException extends ApiException
{
    public function __construct(int $productId)
    {
        parent::__construct(sprintf('Product with ID %d not found', $productId));
    }
}
