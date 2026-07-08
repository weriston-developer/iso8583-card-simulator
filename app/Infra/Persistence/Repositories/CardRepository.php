<?php

namespace App\Infra\Persistence\Repositories;

use App\Domain\Entities\CardEntity;
use App\Infra\Persistence\Models\Card;
use App\Infra\Persistence\Repositories\Interface\CardInterface;

class CardRepository implements CardInterface
{
    public function __construct(
        private readonly Card $cardModel,
    ) {}

    public function findByUuid(string $uuid): ?CardEntity
    {
        $card = $this->cardModel->where('uuid', $uuid)->first();

        if (!$card) {
            return null;
        }

        return CardEntity::fromModel($card);
    }
}