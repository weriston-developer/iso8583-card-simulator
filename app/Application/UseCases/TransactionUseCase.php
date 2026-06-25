<?php

namespace App\Application\UseCases;

use App\Application\DTOs\Outputs\TransactionOutput;
use App\Application\DTOs\Inputs\TransactionInput;
use App\Domain\Entities\CardEntity;
use App\Domain\Entities\TransactionEntity;
use App\Domain\Services\TransactionService;
use App\Domain\VOs\MoneyVO;
use App\Infra\Persistence\Repositories\Interface\BalanceInterface;
use App\Infra\Persistence\Repositories\Interface\CardInterface;
use App\Infra\Persistence\Repositories\Interface\TransactionInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;

class TransactionUseCase
{

    /**
     * $input->cardInput->cardId é o Identificador externo conhecido do cartão (UUID)
     * $transactionId é o Identificador externo gerado da transação (UUID)
     * TransactionUseCase
     * @param CardInterface $cardRepository
     * @param TransactionInterface $transactionRepository
     * @param BalanceInterface $balanceRepository
     * @param TransactionService $transactionService
     */

    public function __construct(
        private readonly CardInterface $cardRepository,
        private readonly TransactionInterface $transactionRepository,
        private readonly BalanceInterface $balanceRepository,
        private readonly TransactionService $transactionService,
    ) {}
    public function execute(TransactionInput $input): TransactionOutput
    {
        $balance = null;
        $authorizationCode = '00';

        $card = $this->cardRepository->findByUuid((string) $input->cardInput->cardUuid);

        if (!$card) {
            $authorizationCode = '02';
        }

        if ($authorizationCode === '00' && !$card?->cardEnabled()) {
            $authorizationCode = '03';
        }

        if ($authorizationCode === '00' && $card?->exceedsMonthlyLimit($input->amount->totalAmount)) {
            $authorizationCode = '08';
        }

        if ($card?->id !== null) {
            $balance = $this->balanceRepository->getBalanceByCardId((string) $card->id);
        }

        $transaction = $this->transactionRepository->findByTransactionUuid($input->transactionUuid);

        if ($transaction?->exists($input->transactionUuid)) {
            $authorizationCode = '07';

            $this->createTransaction(
                input: $input,
                card: $card,
                authorizationCode: $authorizationCode,
                balanceAfterPurchase: null,
                currentBalance: $balance,
            );

            return TransactionOutput::fromAuthorizationCode(
                authorizationCode: $authorizationCode,
                balanceAmount: null,
                balanceCurrencyCode: null,
            );
        }

        if ($authorizationCode === '00' && $balance?->lessThan($input->amount->totalAmount)) {
            $authorizationCode = '01';
        }

        $balanceAfterPurchase = null;
        if ($authorizationCode === '00' && $balance) {
            try {
                $candidateBalance = $balance->subtract($input->amount->totalAmount);

                if ($candidateBalance->lessThan(MoneyVO::zero())) {
                    $authorizationCode = '01';
                } else {
                    $balanceAfterPurchase = $candidateBalance;
                }
            } catch (InvalidArgumentException) {
                $authorizationCode = '01';
            }
        }

        $this->createTransaction($input, $card, $authorizationCode, $balanceAfterPurchase, $balance);

        if ($authorizationCode === '00' && $card && $card->id !== null) {
            $this->balanceRepository->cancelCacheBalanceByCardId((string) $card->id);
        }

        return TransactionOutput::fromAuthorizationCode(
            authorizationCode: $authorizationCode,
            balanceAmount: TransactionOutput::requiresBalance($authorizationCode) && $balance
                ? $balanceAfterPurchase->toCents()
                : null,
            balanceCurrencyCode: TransactionOutput::requiresBalance($authorizationCode) ? 986 : null,
        );
    }

    private function createTransaction(
        TransactionInput $input,
        ?CardEntity $card,
        string $authorizationCode,
        ?MoneyVO $balanceAfterPurchase,
        ?MoneyVO $currentBalance,
    ): void {
        $transactionEntity = TransactionEntity::fromArray([
            'uuid' => Str::uuid()->toString(),
            'transaction_uuid' => $input->transactionUuid,
            'transaction_type' => $input->transactionType,
            'card_uuid' => (string) ($card->uuid ?? $input->cardInput->cardUuid),
            'request_mti' => $input->iso8583MessageInput->requestMti,
            'request_card_number' => $input->iso8583MessageInput->requestCardNumber,
            'request_processing_code' => $input->iso8583MessageInput->requestProcessingCode,
            // Valor original conforme entrou na transacao (antes de conversao)
            'request_transaction_amount_local_original' => $input->amount->originalAmount->toCents(),
            // Valor convertido para moeda local (BR)
            'request_transaction_amount_local' => $input->amount->totalAmount->toCents(),
            'request_transaction_amount_referencia' => $input->amount->totalAmount->toCents(),
            'request_amount_in_card_holder_billing' => $input->iso8583MessageInput->requestAmountInCardHolderBilling,
            'request_transmition_date_and_time' => $input->iso8583MessageInput->requestTransmitionDateAndTime,
            'request_convertion_rate_card_holder_billing' => $input->iso8583MessageInput->requestConvertionRateCardHolderBilling,
            'request_system_trace_audit_number' => $input->iso8583MessageInput->requestSystemTraceAuditNumber,
            'request_local_transaction_time' => $input->iso8583MessageInput->requestLocalTransactionTime,
            'request_local_transaction_date' => $input->iso8583MessageInput->requestLocalTransactionDate,
            'request_expiration_date' => $input->iso8583MessageInput->requestExpirationDate,
            'request_mcc' => $input->establishmentInput->mcc,
            'request_acquiring_institution_country_code' => $input->iso8583MessageInput->requestAcquiringInstitutionCountryCode ?? $input->countryCode,
            'request_pos_entry_mode' => $input->iso8583MessageInput->requestPosEntryMode ?? $input->entryMode,
            'request_pos_condition_code' => $input->iso8583MessageInput->requestPosConditionCode,
            'request_aquiring_institution_code' => $input->iso8583MessageInput->requestAquiringInstitutionCode,
            'request_retrieval_reference_number' => $input->iso8583MessageInput->requestRetrievalReferenceNumber,
            'request_authorization_response_code' => $authorizationCode,
            'request_card_acceptor_terminal' => $input->iso8583MessageInput->requestCardAcceptorTerminal,
            'request_card_acceptor_identification_code' => $input->iso8583MessageInput->requestCardAcceptorIdentificationCode,
            'request_card_acceptor_name_location' => $input->iso8583MessageInput->requestCardAcceptorNameLocation,
            'request_contains_pds_in_ltv_format' => $input->iso8583MessageInput->requestContainsPdsInLtvFormat,
            'request_transaction_currency_code' => $input->amount->totalCurrencyCode,
            'request_transaction_amount' => $input->iso8583MessageInput->requestTransactionAmount,
            'request_currency_code_cardholder_billing' => $input->iso8583MessageInput->requestCurrencyCodeCardholderBilling,
            'request_ps_product_code' => $input->psProductCode,
            // RESPONSE FIELDS - Dados da resposta
            'response_mti' => $input->transactionType === 'PURCHASE' ? '0110' : '0210',
            'response_card_number' => $card?->lastFourDigits ?? $input->cardInput->cardLastFourDigits,
            'response_processing_code' => $input?->iso8583MessageInput->requestProcessingCode,
            'response_transaction_amount_local' => $authorizationCode === '00'
                ? $balanceAfterPurchase?->toCents()
                : $currentBalance?->toCents(),
            'response_acquiring_institution_country_code' => $input?->countryCode,
            'response_pos_condition_code' => $input?->iso8583MessageInput->requestPosConditionCode,
            'response_aquiring_institution_code' => $input?->iso8583MessageInput->requestAquiringInstitutionCode,
            'response_retrieval_reference_number' => $input?->iso8583MessageInput->requestRetrievalReferenceNumber,
            'response_authorization_identification_response' => null,
            'response_code' => $authorizationCode,
            'response_card_acceptor_terminal' => $input?->iso8583MessageInput->requestCardAcceptorTerminal,
            'response_card_acceptor_identification_code' => $input?->iso8583MessageInput->requestCardAcceptorIdentificationCode,
            'response_card_acceptor_name_location' => $input?->iso8583MessageInput->requestCardAcceptorNameLocation,
            'response_transaction_currency_code' => $input?->iso8583MessageInput->requestTransactionCurrencyCode,
            'response_currency_code_cardholder_billing' => $input?->iso8583MessageInput->requestCurrencyCodeCardholderBilling,

        ]);

        $this->transactionRepository->create($transactionEntity);

        $this->transactionService->executePayment($transactionEntity);
    }
}
