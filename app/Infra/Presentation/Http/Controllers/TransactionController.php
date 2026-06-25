<?php

namespace App\Infra\Presentation\Http\Controllers;

use App\Application\UseCases\PurchaseUseCase;
use App\Infra\Presentation\Http\Controllers\Controller;
use App\Infra\Presentation\Http\Controllers\Requests\PurchaseRequest;

class TransactionController extends Controller
{
    public function purchase(PurchaseRequest $request, PurchaseUseCase $useCase)
    {
        $input = $request->toInput();
        $output = $useCase->execute($input);

        return response()->json($output->getData(), $output->getStatusCode());
    }
}