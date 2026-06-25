<?php

namespace App\Infra\Persistence\Repositories\Interface;

use App\Domain\Entities\TransactionEntity;

interface TransactionInterface
{
    public function create(TransactionEntity $data): void;

    public function findByTransactionUuid(string $transactionUuid): ?TransactionEntity;
}