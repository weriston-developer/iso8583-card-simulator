<?php

namespace App\Application\DTOs\Inputs;

use App\Application\DTOs\Inputs\Info\AmountInput;
use App\Application\DTOs\Inputs\Info\AuthorizationInput;
use App\Application\DTOs\Inputs\Info\CardInput;
use App\Application\DTOs\Inputs\Info\EstablishmentInput;
use App\Application\DTOs\Inputs\Info\TransactionMessageInput;

final class ChargeBackInput
{
    public function __construct(
        public readonly string $chargeBackUuid,
        public readonly string $transactionOriginalUuid,
        public readonly string $transactionType,
        public readonly string $psProductCode,
        public readonly string $psProductName,
        public readonly string $countryCode,
        public readonly string $preAuthorization,
        public readonly string $entryMode,
        public readonly AuthorizationInput $authorization,
        public readonly bool $internacional,
        public readonly ?array $fees,
        public readonly string $brand,
        public readonly EstablishmentInput $establishmentInput,
        public readonly AmountInput $amount,
        public readonly CardInput $cardInput,
        public readonly TransactionMessageInput $iso8583MessageInput,
        public readonly bool $forceAccept,
        public readonly string $platform,
    ) {}
}
