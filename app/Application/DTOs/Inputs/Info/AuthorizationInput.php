<?php

namespace App\Application\DTOs\Inputs\Info;

final class AuthorizationInput
{
    public function __construct(
        public readonly string $code,
        public readonly string $description
    ) {
    }
}