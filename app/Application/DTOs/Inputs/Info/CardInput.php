<?php

namespace App\Application\DTOs\Inputs\Info;

final class CardInput
{
    public function __construct(
        public readonly int $cardId,
        public readonly string $cardLastFourDigits,
        public readonly string $cardInput,
        public readonly string $bin,
    ) {
    }
}