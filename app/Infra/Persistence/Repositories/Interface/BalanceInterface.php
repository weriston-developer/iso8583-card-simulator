<?php

namespace App\Infra\Persistence\Repositories\Interface;

use App\Domain\VOs\MoneyVO;

interface BalanceInterface
{
    public function getBalanceByCardId(string $cardId): MoneyVO;

    public function cancelCacheBalanceByCardId(string $cardId): void;
}