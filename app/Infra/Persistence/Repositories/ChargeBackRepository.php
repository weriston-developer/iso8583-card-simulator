<?php

namespace App\Infra\Persistence\Repositories;

use App\Domain\Entities\ChargeBackEntity;
use App\Domain\VOs\MoneyVO;
use App\Infra\Persistence\Models\ChargeBack;
use App\Infra\Persistence\Repositories\Interface\ChargeBackInterface;

class ChargeBackRepository implements ChargeBackInterface
{
   public function __construct(
        private readonly ChargeBack $chargeBackModel,
    ) {}

    public function create(ChargeBackEntity $data): void
    {
        $this->chargeBackModel->create(
            $data->toArray()
        );
    }

    public function findAllChargeBackByTransactionUuidValue(string $transactionUuid): MoneyVO
    {
        $totalChargeBack = $this->chargeBackModel
            ->where('transaction_uuid', $transactionUuid)
            ->sum('requestOriginalTransactionAmount');

        return MoneyVO::fromCents($totalChargeBack);
    }
}