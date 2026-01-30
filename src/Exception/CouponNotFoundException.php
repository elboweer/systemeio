<?php

declare(strict_types=1);

namespace App\Exception;

final class CouponNotFoundException extends ApiException
{
    public function __construct(string $couponCode)
    {
        parent::__construct(sprintf('Coupon "%s" not found', $couponCode));
    }
}
