<?php

namespace App\Application\DTOs\Outputs;

/**
 * Output para resposta de compra
 *
 * Padroniza formato de saída para o cliente
 * Compatível com responseCodeTransition do Helper
 */
readonly class TransactionOutput
{
    private function __construct(
        public int $statusCode,
        public array $data
    ) {}

    /**
     * Determina se o código de autorização requer informação de balance na resposta
     *
     * Códigos que PRECISAM de balance:
     * - '00': Operação bem-sucedida (retorna balance atualizado)
     * - '01': Saldo insuficiente (precisa mostrar saldo atual)
     * - '03': Cartão inválido/inativo (pode retornar saldo atual)
     *
     * Códigos que NÃO precisam de balance:
     * - '02': Cartão não encontrado
     * - '04': Valor inválido (erro de validação)
     * - '05': Suspeita de fraude (erro de segurança)
     * - '06': Produto não permitido (erro de validação)
     * - '07': Transação duplicada (erro de negócio)
     * - '08': Valor excedido (erro de validação)
     * - '09', '10': Transação não encontrada (erro de validação)
     * - '14': Cartão inválido (erro de validação)
     * - '60', '96': Erros de sistema
     * - Outros códigos de erro
     *
     * @param  string  $authorizationCode  Código de autorização
     * @return bool True se requer balance
     */
    public static function requiresBalance(string $authorizationCode): bool
    {
        // Códigos que precisam de balance
        $balanceRequiredCodes = ['00', '01', '03'];

        return in_array($authorizationCode, $balanceRequiredCodes, true);
    }

    /**
     * Cria resposta baseada no código de autorização
     * Segue a mesma estrutura do Helper::responseCodeTransition
     *
     * @param  string  $authorizationCode  Código de autorização ('00', '01', '02', etc)
     * @param  int|null  $authorizationId  ID da transação (obrigatório para '00')
     * @param  int|null  $balanceAmount  Saldo em centavos
     * @param  int|null  $balanceCurrencyCode  Código da moeda
     */
    public static function fromAuthorizationCode(
        string $authorizationCode,
        ?int $authorizationId = null,
        ?int $balanceAmount = null,
        ?int $balanceCurrencyCode = null
    ): self {
        return match ($authorizationCode) {
            '00' => new self(
                statusCode: 200,
                data: [
                    'message' => 'Operação realizada com sucesso.',
                    'code' => 0,
                    'authorization_id' => $authorizationId,
                    'authorization_code' => '00',
                    'balance' => [
                        'amount' => $balanceAmount,
                        'currency_code' => $balanceCurrencyCode,
                    ],
                    'purchaseOnlyApproval' => null,
                    'purchaseOnlyPartialAmountApproved' => null,
                    'cashbackOnlyPartialAmountApproved' => null,
                ]
            ),
            '01' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Saldo insuficiente.',
                    'balance' => [
                        'amount' => $balanceAmount,
                        'currency_code' => $balanceCurrencyCode,
                    ],
                    'code' => 530,
                    'authorization_code' => '01',
                    'useVoucher' => false,
                ]
            ),
            '02' => new self(
                statusCode: 404,
                data: [
                    'message' => 'Cartão não encontrado.',
                    'code' => 111,
                    'authorization_code' => '02',
                ]
            ),
            '03' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Cartão inválido ou inativo.',
                    'balance' => [
                        'amount' => $balanceAmount,
                        'currency_code' => $balanceCurrencyCode,
                    ],
                    'code' => 530,
                    'authorization_code' => '03',
                ]
            ),
            '04' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Valor inválido baseado na decisão do emissor.',
                    'code' => 530,
                    'authorization_code' => '04',
                ]
            ),
            '05' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Suspeita de fraude, transação negada.',
                    'code' => 530,
                    'authorization_code' => '05',
                ]
            ),
            '06' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Produto não permitido para este cartão.',
                    'code' => 530,
                    'authorization_code' => '06',
                ]
            ),
            '07' => new self(
                statusCode: 409,
                data: [
                    'message' => 'Operação já feita.',
                    'code' => 140,
                    'authorization_code' => '07',
                ]
            ),
            '08' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Valor da transação excede o permitido.',
                    'code' => 530,
                    'authorization_code' => '08',
                ]
            ),
            '09' => new self(
                statusCode: 404,
                data: [
                    'message' => 'Identificador original não encontrado.',
                    'code' => 404,
                    'authorization_code' => '09',
                ]
            ),
            '10' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Identificadores de estorno não encontrados ou valor de cancelamento excede o valor da compra.',
                    'code' => 530,
                    'authorization_code' => '10',
                ]
            ),
            '14' => new self(
                statusCode: 400,
                data: [
                    'message' => 'Cartão inválido.',
                    'code' => 530,
                    'authorization_code' => '14',
                ]
            ),
            '60' => new self(
                statusCode: 500,
                data: [
                    'message' => 'Erro ao processar transação.',
                    'code' => 60,
                    'authorization_code' => '60',
                ]
            ),
            '96' => new self(
                statusCode: 500,
                data: [
                    'message' => 'Sistema indisponível. Erro ao processar transação.',
                    'code' => 900,
                    'authorization_code' => '96',
                ]
            ),
            default => new self(
                statusCode: 500,
                data: [
                    'message' => 'Sistema indisponível. Erro ao processar transação.',
                    'code' => 900,
                    'authorization_code' => $authorizationCode,
                ]
            ),
        };
    }

    /**
     * Cria resposta de sucesso
     *
     * @deprecated Use fromAuthorizationCode('00', ...) instead
     */
    public static function success(
        string $authorizationId,
        int $balanceAmount,
        string $balanceCurrencyCode,
        array $additionalData = []
    ): self {
        return new self(
            statusCode: 200,
            data: array_merge([
                'message' => 'Operação realizada com sucesso.',
                'code' => 0,
                'authorization_id' => (int) $authorizationId,
                'balance' => [
                    'amount' => $balanceAmount,
                    'currency_code' => $balanceCurrencyCode,
                ],
                'purchaseOnlyApproval' => null,
                'purchaseOnlyPartialAmountApproved' => null,
                'cashbackOnlyPartialAmountApproved' => null,
            ], $additionalData)
        );
    }

    /**
     * Cria resposta de erro
     *
     * @deprecated Use fromAuthorizationCode($code, ...) instead
     */
    public static function error(
        string $code,
        string $message,
        int $statusCode = 400,
        array $additionalData = []
    ): self {
        return new self(
            statusCode: $statusCode,
            data: array_merge([
                'code' => $code,
                'message' => $message,
            ], $additionalData)
        );
    }

    /**
     * Retorna status code HTTP
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Retorna dados da resposta
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->data,
            ['status_code' => $this->statusCode]
        );
    }
}
