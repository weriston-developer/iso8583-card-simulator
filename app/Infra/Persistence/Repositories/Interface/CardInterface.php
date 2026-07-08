<?php

namespace App\Infra\Persistence\Repositories\Interface;

use App\Domain\Entities\CardEntity;

interface CardInterface
{
    public function findByUuid(string $uuid): ?CardEntity;
}