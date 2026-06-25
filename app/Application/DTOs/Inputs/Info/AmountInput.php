<?php

namespace App\Application\DTOs\Inputs\Info;

use App\Domain\VOs\MoneyVO;

final class AmountInput
{
    public function __construct(
        public readonly MoneyVO $totalAmount,
        public readonly string $totalCurrencyCode,
        public readonly MoneyVO $originalAmount,
        public readonly string $originalCurrencyCode,
    ) {}
}
