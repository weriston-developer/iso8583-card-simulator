<?php

namespace App\Application\DTOs\Inputs\Info;

final class ProcessCodingInput
{
    public function __construct(
        public readonly string $typeProcessingCode,
        public readonly string $sourceAccountType,
        public readonly string $destinationAccountType
    ) {}
}