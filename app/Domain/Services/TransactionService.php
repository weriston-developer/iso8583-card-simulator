<?php

namespace App\Domain\Services;

use App\Domain\Entities\TransactionEntity;
use App\Domain\VOs\MoneyVO;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
    ) {}

    /**
     * Executa a transação
     */
    public function executePayment(TransactionEntity $transactionEntity): void
    {
        if ($transactionEntity->isDeclined()) {
            return;
        }

        $this->insertMovementCard(
            $transactionEntity->getTransactionUuid(),
            $transactionEntity->getCardUuid(),
            $transactionEntity->getValue(),
            $transactionEntity->getDescription()
        );
    }

    private function insertMovementCard(
        string $transactionUuid,
        string $cardUuid,
        MoneyVO $value,
        string $description
    ): void {
        DB::update(
            'EXEC [sp_insert_movement_card]
            @transactionUuid = ?,
            @cardUuid = ?,
            @value = ?,
            @tax_value = ?,
            @description = ?',
            [
                $transactionUuid,
                $cardUuid,
                $value->toCents(),
                $description,
            ]
        );
    }
}
