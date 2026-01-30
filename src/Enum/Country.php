<?php

declare(strict_types=1);

namespace App\Enum;

enum Country: string
{
    case GERMANY = 'DE';
    case ITALY = 'IT';
    case GREECE = 'GR';
    case FRANCE = 'FR';

    /**
     * Налоговая ставка в процентах
     */
    public function getTaxRate(): int
    {
        return match ($this) {
            self::GERMANY => 19,
            self::ITALY => 22,
            self::GREECE => 24,
            self::FRANCE => 20,
        };
    }

    /**
     * Паттерн для валидации
     */
    public function getTaxNumberPattern(): string
    {
        return match ($this) {
            self::GERMANY => '/^DE\d{9}$/',           // DE + 9 цифр
            self::ITALY => '/^IT\d{11}$/',            // IT + 11 цифр
            self::GREECE => '/^GR\d{9}$/',            // GR + 9 цифр
            self::FRANCE => '/^FR[A-Za-z]{2}\d{9}$/', // FR + 2 буквы + 9 цифр
        };
    }

    public static function fromTaxNumber(string $taxNumber): ?self
    {
        $prefix = substr($taxNumber, 0, 2);

        $country = self::tryFrom($prefix);
        if ($country === null) {
            return null;
        }

        // Проверяем полный формат
        if (!preg_match($country->getTaxNumberPattern(), $taxNumber)) {
            return null;
        }

        return $country;
    }
}
