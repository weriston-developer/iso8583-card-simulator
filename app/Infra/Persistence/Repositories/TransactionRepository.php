<?php

namespace App\Infra\Persistence\Repositories;

use App\Domain\Entities\TransactionEntity;
use App\Infra\Persistence\Repositories\Interface\TransactionInterface;

class TransactionRepository implements TransactionInterface
{
    public function __construct(
        private readonly \App\Models\TransactionModel $transactionModel,
    ) {}

    public function create(TransactionEntity $data): void
    {
        $this->transactionModel->create(
            $data->toArray()
        );
    }

    public function findByTransactionId(string $transactionId): ?TransactionEntity
    {
        $transaction = $this->transactionModel->where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return null;
        }

        return TransactionEntity::fromModel($transaction);
    }
}
