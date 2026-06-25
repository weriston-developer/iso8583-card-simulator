<?php

namespace App\Domain\Entities;

use App\Domain\VOs\MoneyVO;
use App\Infra\Persistence\Models\ChargeBack;

class ChargeBackEntity
{
    public function __construct(
        public readonly string $chargeBackUuid,
        public readonly string $transactionOriginalUuid,
        public readonly string $transactionType,
        public readonly string $psProductCode,
        public readonly string $psProductName,
        public readonly string $countryCode,
        public readonly string $preAuthorization,
        public readonly string $entryMode,
        public readonly ?int $id,
        public readonly ?string $uuid,

        // Request Fields - ISO8583
        public readonly ?string $requestMti,
        public readonly ?string $requestCardNumber,
        public readonly ?string $requestProcessingCode,
        public readonly ?string $requestPsProductCode,
        public readonly ?int $requestOriginalTransactionAmountValue,
        public readonly ?MoneyVO $requestOriginalTransactionAmount,
        public readonly ?MoneyVO $requestTransactionAmount,
        public readonly ?string $requestTransmitionDateAndTime,
        public readonly ?string $requestConvertionRateCardHolderBilling,
        public readonly ?string $requestSystemTraceAuditNumber,
        public readonly ?string $requestLocalTransactionTime,
        public readonly ?string $requestLocalTransactionDate,
        public readonly ?string $requestExpirationDate,
        public readonly ?string $requestMcc,
        public readonly ?string $requestAcquiringInstitutionCountryCode,
        public readonly ?string $requestPosEntryMode,
        public readonly ?string $requestPosConditionCode,
        public readonly ?string $requestAquiringInstitutionCode,
        public readonly ?string $requestRetrievalReferenceNumber,
        public readonly ?string $requestResponseCode,
        public readonly ?string $requestCardAcceptorTerminal,
        public readonly ?string $requestCardAcceptorIdentificationCode,
        public readonly ?string $requestCardAcceptorNameLocation,
        public readonly ?string $requestTransactionCurrencyCode,
        public readonly ?string $requestTransactionCurrencyCode2,
        public readonly ?string $requestCurrencyCodeCardholderBilling,
        public readonly ?string $requestValues,
        public readonly ?string $requestReplacementAmounts,
        // Response Fields - ISO8583
        public readonly ?string $responseMti,
        public readonly ?string $responseCardNumber,
        public readonly ?string $responseProcessingCode,
        public readonly ?MoneyVO $responseTransactionAmountLocal,
        public readonly ?MoneyVO $responseAmountInCardHolderBilling,
        public readonly ?string $responseTransmitionDateAndTime,
        public readonly ?string $responseConversionRate,
        public readonly ?string $responseSystemTraceAuditNumber,
        public readonly ?string $responseAcquiringInstitutionCountryCode,
        public readonly ?string $responsePosConditionCode,
        public readonly ?string $responseAquiringInstitutionCode,
        public readonly ?string $responseRetrievalReferenceNumber,
        public readonly ?string $responseAuthorizationIdentificationResponse,
        public readonly ?string $responseCode,
        public readonly ?string $responseCardAcceptorTerminal,
        public readonly ?string $responseCardAcceptorIdentificationCode,
        public readonly ?string $responseCardAcceptorNameLocation,
        public readonly ?string $responseTransactionCurrencyCode,
        public readonly ?string $responseCurrencyCodeCardholderBilling
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            chargeBackUuid: $data['charge_back_uuid'],
            transactionOriginalUuid: $data['transaction_original_uuid'],
            transactionType: $data['transaction_type'],
            psProductCode: $data['ps_product_code'],
            psProductName: $data['ps_product_name'],
            countryCode: $data['country_code'],
            preAuthorization: $data['pre_authorization'],
            entryMode: $data['entry_mode'],
            id: $data['id'] ?? null,
            uuid: $data['uuid'] ?? null,
            requestMti: $data['request_mti'] ?? null,
            requestCardNumber: $data['request_card_number'] ?? null,
            requestProcessingCode: $data['request_processing_code'] ?? null,
            requestPsProductCode: $data['request_ps_product_code'] ?? null,
            requestOriginalTransactionAmountValue: $data['request_original_transaction_amount_value'] ?? null,
            requestOriginalTransactionAmount: isset($data['request_original_transaction_amount']) ? MoneyVO::fromCents($data['request_original_transaction_amount']) : null,
            requestTransactionAmount: isset($data['request_transaction_amount']) ? MoneyVO::fromCents($data['request_transaction_amount']) : null,
            requestTransmitionDateAndTime: $data['request_transmition_date_and_time'] ?? null,
            requestConvertionRateCardHolderBilling: $data['request_convertion_rate_card_holder_billing'] ?? null,
            requestSystemTraceAuditNumber: $data['request_system_trace_audit_number'] ?? null,
            requestLocalTransactionTime: $data['request_local_transaction_time'] ?? null,
            requestLocalTransactionDate: $data['request_local_transaction_date'] ?? null,
            requestExpirationDate: $data['request_expiration_date'] ?? null,
            requestMcc: $data['request_mcc'] ?? null,
            requestAcquiringInstitutionCountryCode: $data['request_acquiring_institution_country_code'] ?? null,
            requestPosEntryMode: $data['request_pos_entry_mode'] ?? null,
            requestPosConditionCode: $data['request_pos_condition_code'] ?? null,
            requestAquiringInstitutionCode: $data['request_aquiring_institution_code'] ?? null,
            requestRetrievalReferenceNumber: $data['request_retrieval_reference_number'] ?? null,
            requestResponseCode: $data['request_response_code'] ?? null,
            requestCardAcceptorTerminal: $data['request_card_acceptor_terminal'] ?? null,
            requestCardAcceptorIdentificationCode: $data['request_card_acceptor_identification_code'] ?? null,
            requestCardAcceptorNameLocation: $data['request_card_acceptor_name_location'] ?? null,
            requestTransactionCurrencyCode: $data['request_transaction_currency_code'] ?? null,
            requestTransactionCurrencyCode2: $data['request_transaction_currency_code_2'] ?? null,
            requestCurrencyCodeCardholderBilling: $data['request_currency_code_cardholder_billing'] ?? null,
            requestValues: $data['request_values'] ?? null,
            requestReplacementAmounts: $data['request_replacement_amounts'] ?? null,
            responseMti: $data['response_mti'] ?? null,
            responseCardNumber: $data['response_card_number'] ?? null,
            responseProcessingCode: $data['response_processing_code'] ?? null,
            responseTransactionAmountLocal: isset($data['response_transaction_amount_local']) ? MoneyVO::fromCents($data['response_transaction_amount_local']) : null,
            responseAmountInCardHolderBilling: isset($data['response_amount_in_card_holder_billing']) ? MoneyVO::fromCents($data['response_amount_in_card_holder_billing']) : null,
            responseTransmitionDateAndTime: $data['response_transmition_date_and_time'] ?? null,
            responseConversionRate: $data['response_conversion_rate'] ?? null,
            responseSystemTraceAuditNumber: $data['response_system_trace_audit_number'] ?? null,
            responseAcquiringInstitutionCountryCode: $data['response_acquiring_institution_country_code'] ?? null,
            responsePosConditionCode: $data['response_pos_condition_code'] ?? null,
            responseAquiringInstitutionCode: $data['response_aquiring_institution_code'] ?? null,
            responseRetrievalReferenceNumber: $data['response_retrieval_reference_number'] ?? null,
            responseAuthorizationIdentificationResponse: $data['response_authorization_identification_response'] ?? null,
            responseCode: $data['response_code'] ?? null,
            responseCardAcceptorTerminal: $data['response_card_acceptor_terminal'] ?? null,
            responseCardAcceptorIdentificationCode: $data['response_card_acceptor_identification_code'] ?? null,
            responseCardAcceptorNameLocation: $data['response_card_acceptor_name_location'] ?? null,
            responseTransactionCurrencyCode: $data['response_transaction_currency_code'] ?? null,
            responseCurrencyCodeCardholderBilling: $data['response_currency_code_cardholder_billing'] ?? null,
        );
    }

    public static function fromModel(ChargeBack $model): self
    {
        return new self(
            chargeBackUuid: $model->charge_back_uuid,
            transactionOriginalUuid: $model->transaction_original_uuid,
            transactionType: $model->transaction_type,
            psProductCode: $model->ps_product_code,
            psProductName: $model->ps_product_name,
            countryCode: $model->country_code,
            preAuthorization: $model->pre_authorization,
            entryMode: $model->entry_mode,
            id: $model->id,
            uuid: $model->uuid,
            requestMti: $model->request_mti,
            requestCardNumber: $model->request_card_number,
            requestProcessingCode: $model->request_processing_code,
            requestPsProductCode: $model->request_ps_product_code,
            requestOriginalTransactionAmountValue: $model->request_original_transaction_amount_value,
            requestOriginalTransactionAmount: isset($model->request_original_transaction_amount) ? MoneyVO::fromCents($model->request_original_transaction_amount) : null,
            requestTransactionAmount: isset($model->request_transaction_amount) ? MoneyVO::fromCents($model->request_transaction_amount) : null,
            requestTransmitionDateAndTime: $model->request_transmition_date_and_time,
            requestConvertionRateCardHolderBilling: $model->request_convertion_rate_card_holder_billing,
            requestSystemTraceAuditNumber: $model->request_system_trace_audit_number,
            requestLocalTransactionTime: $model->request_local_transaction_time,
            requestLocalTransactionDate: $model->request_local_transaction_date,
            requestExpirationDate: $model->request_expiration_date,
            requestMcc: $model->request_mcc,
            requestAcquiringInstitutionCountryCode: $model->request_acquiring_institution_country_code,
            requestPosEntryMode: $model->request_pos_entry_mode,
            requestPosConditionCode: $model->request_pos_condition_code,
            requestAquiringInstitutionCode: $model->request_aquiring_institution_code,
            requestRetrievalReferenceNumber: $model->request_retrieval_reference_number,
            requestResponseCode: $model->request_response_code,
            requestCardAcceptorTerminal: $model->request_card_acceptor_terminal,
            requestCardAcceptorIdentificationCode: $model->request_card_acceptor_identification_code,
            requestCardAcceptorNameLocation: $model->request_card_acceptor_name_location,
            requestTransactionCurrencyCode: $model->request_transaction_currency_code,
            requestTransactionCurrencyCode2: $model->request_transaction_currency_code_2,
            requestCurrencyCodeCardholderBilling: $model->request_currency_code_cardholder_billing,
            requestValues: $model->request_values,
            requestReplacementAmounts: $model->request_replacement_amounts,
            responseMti: $model->response_mti,
            responseCardNumber: $model->response_card_number,
            responseProcessingCode: $model->response_processing_code,
            responseTransactionAmountLocal: isset($model->response_transaction_amount_local) ? MoneyVO::fromCents($model->response_transaction_amount_local) : null,
            responseAmountInCardHolderBilling: isset($model->response_amount_in_card_holder_billing) ? MoneyVO::fromCents($model->response_amount_in_card_holder_billing) : null,
            responseTransmitionDateAndTime: $model->response_transmition_date_and_time,
            responseConversionRate: $model->response_conversion_rate,
            responseSystemTraceAuditNumber: $model->response_system_trace_audit_number,
            responseAcquiringInstitutionCountryCode: $model->response_acquiring_institution_country_code,
            responsePosConditionCode: $model->response_pos_condition_code,
            responseAquiringInstitutionCode: $model->response_aquiring_institution_code,
            responseRetrievalReferenceNumber: $model->response_retrieval_reference_number,
            responseAuthorizationIdentificationResponse: $model->response_authorization_identification_response,
            responseCode: $model->response_code,
            responseCardAcceptorTerminal: $model->response_card_acceptor_terminal,
            responseCardAcceptorIdentificationCode: $model->response_card_acceptor_identification_code,
            responseCardAcceptorNameLocation: $model->response_card_acceptor_name_location,
            responseTransactionCurrencyCode: $model->response_transaction_currency_code,
            responseCurrencyCodeCardholderBilling: $model->response_currency_code_cardholder_billing,
        );
    }

    public function toArray(): array
    {
        return [
            'charge_back_uuid' => $this->chargeBackUuid,
            'transaction_original_uuid' => $this->transactionOriginalUuid,
            'transaction_type' => $this->transactionType,
            'ps_product_code' => $this->psProductCode,
            'ps_product_name' => $this->psProductName,
            'country_code' => $this->countryCode,
            'pre_authorization' => $this->preAuthorization,
            'entry_mode' => $this->entryMode,
            'id' => $this->id,
            'uuid' => $this->uuid,
            'request_mti' => $this->requestMti,
            'request_card_number' => $this->requestCardNumber,
            'request_processing_code' => $this->requestProcessingCode,
            'request_ps_product_code' => $this->requestPsProductCode,
            'request_original_transaction_amount_value' => $this->requestOriginalTransactionAmountValue,
            'request_original_transaction_amount' => $this->requestOriginalTransactionAmount?->toCents(),
            'request_transaction_amount' => $this->requestTransactionAmount?->toCents(),
            'request_transmition_date_and_time' => $this->requestTransmitionDateAndTime,
            'request_convertion_rate_card_holder_billing' => $this->requestConvertionRateCardHolderBilling,
            'request_system_trace_audit_number' => $this->requestSystemTraceAuditNumber,
            'request_local_transaction_time' => $this->requestLocalTransactionTime,
            'request_local_transaction_date' => $this->requestLocalTransactionDate,
            'request_expiration_date' => $this->requestExpirationDate,
            'request_mcc' => $this->requestMcc,
            'request_acquiring_institution_country_code' => $this->requestAcquiringInstitutionCountryCode,
            'request_pos_entry_mode' => $this->requestPosEntryMode,
            'request_pos_condition_code' => $this->requestPosConditionCode,
            'request_aquiring_institution_code' => $this->requestAquiringInstitutionCode,
            'request_retrieval_reference_number' => $this->requestRetrievalReferenceNumber,
            'request_response_code' => $this->requestResponseCode,
            'request_card_acceptor_terminal' => $this->requestCardAcceptorTerminal,
            'request_card_acceptor_identification_code' => $this->requestCardAcceptorIdentificationCode,
            'request_card_acceptor_name_location' => $this->requestCardAcceptorNameLocation,
            'request_transaction_currency_code' => $this->requestTransactionCurrencyCode,
            'request_transaction_currency_code_2' => $this->requestTransactionCurrencyCode2,
            'request_currency_code_cardholder_billing' => $this->requestCurrencyCodeCardholderBilling,
            'request_values' => $this->requestValues,
            'request_replacement_amounts' => $this->requestReplacementAmounts,
            'response_mti' => $this->responseMti,
            'response_card_number' => $this->responseCardNumber,
            'response_processing_code' => $this->responseProcessingCode,
            'response_transaction_amount_local' => $this->responseTransactionAmountLocal?->toCents(),
            'response_amount_in_card_holder_billing' => $this->responseAmountInCardHolderBilling?->toCents(),
            'response_transmition_date_and_time' => $this->responseTransmitionDateAndTime,
            'response_conversion_rate' => $this->responseConversionRate,
            'response_system_trace_audit_number' => $this->responseSystemTraceAuditNumber,
            'response_acquiring_institution_country_code' => $this->responseAcquiringInstitutionCountryCode,
            'response_pos_condition_code' => $this->responsePosConditionCode,
            'response_aquiring_institution_code' => $this->responseAquiringInstitutionCode,
            'response_retrieval_reference_number' => $this->responseRetrievalReferenceNumber,
            'response_authorization_identification_response' => $this->responseAuthorizationIdentificationResponse,
            'response_code' => $this->responseCode,
            'response_card_acceptor_terminal' => $this->responseCardAcceptorTerminal,
            'response_card_acceptor_identification_code' => $this->responseCardAcceptorIdentificationCode,
            'response_card_acceptor_name_location' => $this->responseCardAcceptorNameLocation,
            'response_transaction_currency_code' => $this->responseTransactionCurrencyCode,
            'response_currency_code_cardholder_billing' => $this->responseCurrencyCodeCardholderBilling
        ];
    }

    public function isApproved(): bool
    {
        return $this->responseCode === '00';
    }

    public function isDeclined(): bool
    {
        return $this->responseCode !== '00';
    }

    public function exists(string $otherChargeBackUuid): bool
    {
        return $this->chargeBackUuid === $otherChargeBackUuid;
    }

    /**
     * Valida se valor de estorno não excede o disponível
     * 
     * Lógica (extraída de CommonService->checkAmount()):
     * 1. Transação Original: R$ 100,00
     * 2. ChargeBack (Estornos): R$ 60,00 → getTotalReversedAmount
     * 3. Cancelamentos de ChargeBack: R$ 20,00 → getTotalCreditAmount
     * 4. Líquido Estornado: R$ 60 - R$ 20 = R$ 40,00
     * 5. Disponível para Estorno: R$ 100 - R$ 40 = R$ 60,00
     * 6. Valida: Valor Solicitado <= Disponível
     * 
     * @param  MoneyVO  $amountTransaction  Valor da transação original
     * @param  MoneyVO  $totalCreditAmount  Total de cancelamentos de estorno
     * @param  MoneyVO  $requestedAmount  Valor solicitado para estorno (em centavos)
     * 
     * @return bool True se valor está disponível, false se excede
     */

    public function isAmountAvailableForChargeBack(
        MoneyVO $amountTransaction,
        MoneyVO $totalCreditAmount,
        MoneyVO $requestedAmount
    ): bool {
        // Valor líquido já estornado (estornos + o valor do momento)
        $netReversedAmount = $totalCreditAmount->add($requestedAmount);

        // Verificar se a soma do valor solicitado com total já estonado não excede o valor da transação original
        return $amountTransaction->greaterThanOrEqual($netReversedAmount);
    }
}
