<?php

namespace App\Infra\Presentation\Http\Controllers;

use App\Application\UseCases\TransactionUseCase;
use App\Infra\Presentation\Http\Controllers\Controller;
use App\Infra\Presentation\Http\Controllers\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function purchase(TransactionRequest $request, TransactionUseCase $useCase)
    {
        if ($request->input('transaction_type') !== 'PURCHASE') {
            return response()->json(['error' => 'Invalid transaction type'], 400);
        }

        $input = $request->toInput();
        $output = $useCase->execute($input);

        return response()->json($output->getData(), $output->getStatusCode());
    }

     public function WITHDRAWAL(TransactionRequest $request, TransactionUseCase $useCase)
    {
        if ($request->input('transaction_type') !== 'WITHDRAWAL') {
            return response()->json(['error' => 'Invalid transaction type'], 400);
        }

        $input = $request->toInput();
        $output = $useCase->execute($input);

        return response()->json($output->getData(), $output->getStatusCode());
    }
}
