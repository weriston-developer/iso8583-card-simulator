<?php

namespace App\Infra\Persistence\Repositories;

use App\Domain\Entities\TransactionEntity;
use App\Infra\Persistence\Models\Transaction;
use App\Infra\Persistence\Repositories\Interface\TransactionInterface;

class TransactionRepository implements TransactionInterface
{
    public function __construct(
        private readonly Transaction $transactionModel,
    ) {}

    public function create(TransactionEntity $data): void
    {
        $this->transactionModel->create(
            $data->toArray()
        );
    }

    public function findByTransactionUuid(string $transactionUuid): ?TransactionEntity
    {
        $transaction = $this->transactionModel->where('transaction_uuid', $transactionUuid)->first();

        if (!$transaction) {
            return null;
        }

        return TransactionEntity::fromModel($transaction);
    }
}
