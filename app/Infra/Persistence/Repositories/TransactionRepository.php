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
}
