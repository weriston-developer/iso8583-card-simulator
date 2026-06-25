<?php 

namespace App\Domain\Entities;

use App\Infra\Persistence\Models\Card;
use App\Domain\VOs\MoneyVO;

class CardEntity
{
    public function __construct(
        public readonly string $status, //TODO enum CardStatus
        public readonly string $lastFourDigits,
        public readonly ?int $id,
        public readonly ?string $uuid,
        public readonly ?string $cardBrand,
        public readonly ?bool $monthlyLimitEnabled,
        public readonly ?MoneyVO $monthlyLimit,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
            lastFourDigits: $data['last_four_digits'],
            id: $data['id'] ?? null,
            uuid: $data['uuid'] ?? null,
            cardBrand: $data['card_brand'] ?? null,
            monthlyLimitEnabled: $data['monthly_limit_enabled'] ?? null,
            monthlyLimit: isset($data['monthly_limit']) ? MoneyVO::fromCents($data['monthly_limit']) : null,
        );
    }

    public static function fromModel(Card $model): self
    {
        return new self(
            status: $model->status,
            lastFourDigits: $model->last_four_digits,
            id: $model->id,
            uuid: $model->uuid,
            cardBrand: $model->card_brand,
            monthlyLimitEnabled: $model->monthly_limit_enabled,
            monthlyLimit: isset($model->monthly_limit) ? MoneyVO::fromCents($model->monthly_limit) : null,
        );
    }


    public function cardEnabled(): bool
    {
        return $this->status === 'enabled';
    }

    public function monthlyLimitEnabled(): bool
    {
        return $this->monthlyLimitEnabled === true;
    }

    public function getMonthlyLimit(): ?MoneyVO
    {
        return $this->monthlyLimit;
    }

    public function exceedsMonthlyLimit(MoneyVO $amount): bool
    {
        if (!$this->monthlyLimitEnabled()) {
            return false;
        }

        if ($this->monthlyLimit === null) {
            return false;
        }

        return $this->monthlyLimit->lessThan($amount);
    }
}