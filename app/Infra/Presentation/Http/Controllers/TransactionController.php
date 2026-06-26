<?php

namespace App\Infra\Presentation\Http\Controllers;

use App\Application\UseCases\TransactionUseCase;
use App\Infra\Presentation\Http\Controllers\Controller;
use App\Infra\Presentation\Http\Controllers\Requests\TransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{

    public function health()
    {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'message' => 'Operação realizada com sucesso.',
                'code' => 0,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do sistema: ' . $e->getMessage());

            return response()->json([
                'message' => 'Sistema indisponível. Erro ao acessar base de dados.',
                'code' => 900,
            ], 503);
        } catch (\Throwable $t) {
            Log::error('Erro no sistema: ' . $t->getMessage());

            return response()->json([
                'message' => 'Não foi possível executar comando. Erro desconhecido.',
                'code' => 999,

            ], 500);
        }
    }
    
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
