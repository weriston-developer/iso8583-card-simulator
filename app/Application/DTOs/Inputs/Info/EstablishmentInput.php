<?php

namespace App\Application\DTOs\Inputs\Info;

final class EstablishmentInput
{
    public function __construct(
        public readonly string $mcc,
        public readonly string $name,
        public readonly string $city,
        public readonly string $countryCode,
        public readonly string $address,
        public readonly string $zipCode,
        public readonly bool $pat
    ) {
    }
}