<?php

namespace App\Infra\Persistence\Repositories\Interface;

use App\Domain\Entities\ChargeBackEntity;
use App\Domain\VOs\MoneyVO;

interface ChargeBackInterface
{
    public function create(ChargeBackEntity $data): void;

    public function findAllChargeBackByTransactionUuidValue(string $transactionUuid): MoneyVO;
}