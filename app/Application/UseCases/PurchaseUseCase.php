<?php

namespace App\Application\UseCases;

use App\Application\DTOs\Outputs\TransactionOutput;
use App\Application\DTOs\Inputs\PurchaseInput;
use App\Domain\Entities\CardEntity;
use App\Domain\Entities\TransactionEntity;
use App\Domain\VOs\MoneyVO;
use App\Infra\Persistence\Repositories\Interface\BalanceInterface;
use App\Infra\Persistence\Repositories\Interface\CardInterface;
use App\Infra\Persistence\Repositories\Interface\TransactionInterface;

class PurchaseUseCase
{

    public function __construct(
        private readonly CardInterface $cardRepository,
        private readonly TransactionInterface $transactionRepository,
        private readonly BalanceInterface $balanceRepository,
    ) {}
    public function execute(PurchaseInput $input): TransactionOutput
    {
        $card = $this->cardRepository->findByUuid((string) $input->cardInput->cardId);
        $balance = null;
        $authorizationCode = '00';

        if (!$card) {
            $authorizationCode = '02';
        }

        if ($card && !$card->cardEnabled()) {
            $authorizationCode = '03';
        }

        if ($card && $card->exceedsMonthlyLimit($input->amount->totalAmount)) {
            $authorizationCode = '08';
        }

        if ($card && $card->id !== null) {
            $balance = $this->balanceRepository->getBalanceByCardId((string) $card->id);
        }

        if ($authorizationCode === '00' && $balance && $balance->lessThan($input->amount->totalAmount)) {
            $authorizationCode = '01';
        }

        $balanceAfterPurchase = null;
        if ($authorizationCode === '00' && $balance) {
            $balanceAfterPurchase = $balance->subtract($input->amount->totalAmount);
        }

        $this->createTransaction($input, $card, $authorizationCode, $balanceAfterPurchase, $balance);

        if ($authorizationCode === '00' && $card && $card->id !== null) {
            $this->balanceRepository->cancelCacheBalanceByCardId((string) $card->id);
        }

        return TransactionOutput::fromAuthorizationCode(
            authorizationCode: $authorizationCode,
            balanceAmount: TransactionOutput::requiresBalance($authorizationCode) && $balance
                ? $balance->toCents()
                : null,
            balanceCurrencyCode: TransactionOutput::requiresBalance($authorizationCode) ? 986 : null,
        );
    }

    private function createTransaction(
        PurchaseInput $input,
        ?CardEntity $card,
        string $authorizationCode,
        ?MoneyVO $balanceAfterPurchase,
        ?MoneyVO $currentBalance,
    ): void
    {
        $transactionEntity = TransactionEntity::fromArray([
            'transaction_id' => $input->transactionId,
            'transaction_type' => $input->transactionType,
            'card_id' => (string) ($card->id ?? $input->cardInput->cardId),
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
            'response_mti' => '0110',
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

        //TODO: Simular o debito do valor da transacao no saldo do cartao
    }
}
