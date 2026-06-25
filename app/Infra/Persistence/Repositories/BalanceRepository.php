<?php 

namespace App\Infra\Persistence\Repositories;

use App\Domain\VOs\MoneyVO;
use App\Infra\Persistence\Repositories\Interface\BalanceInterface;
use Illuminate\Support\Facades\Cache;

class BalanceRepository implements BalanceInterface
{
    private const BALANCE_CACHE_TTL_MINUTES = 10;

    public function __construct(
        private readonly \App\Models\BalanceModel $balanceModel,
    ) {}

    public function getBalanceByCardId(string $cardId): MoneyVO
    {
        $amount = Cache::store('redis')->remember(
            $this->balanceCacheKey($cardId),
            now()->addMinutes(self::BALANCE_CACHE_TTL_MINUTES),
            function () use ($cardId): int {
                return (int) ($this->balanceModel->where('card_id', $cardId)->value('amount') ?? 0);
            }
        );

        return MoneyVO::fromCents((int) $amount);
    }

    public function cancelCacheBalanceByCardId(string $cardId): void
    {
        Cache::store('redis')->forget($this->balanceCacheKey($cardId));
    }

    private function balanceCacheKey(string $cardId): string
    {
        return "balance:card:{$cardId}";
    }
}