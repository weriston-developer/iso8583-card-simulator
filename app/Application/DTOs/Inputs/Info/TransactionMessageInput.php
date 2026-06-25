<?php

namespace App\Application\DTOs\Inputs\Info;

/**
 * DTO para mensagens de transação ISO8583
 *
 * Representa os campos de uma mensagem ISO8583 parseada conforme padrão financeiro.
 * Encapsula todos os Data Elements (DE) da mensagem de forma tipada e estruturada.
 *
 * Fonte: Helper::setISO() - app/Helpers/Helper.php
 */
final class TransactionMessageInput
{
    private function __construct(
        // Message Type Indicator
        public readonly ?string $requestMti,
        // DE002 - Primary Account Number (PAN)
        public readonly ?string $requestCardNumber,
        // DE003 - Processing Code
        public readonly ?string $requestProcessingCode,
        // DE004 - Transaction Amount (Local)
        public readonly ?string $requestTransactionAmountLocal,
        // DE005 - Transaction Amount (Reference)
        public readonly ?string $requestTransactionAmountReferencia,
        // DE006 - Amount in Cardholder Billing
        public readonly ?string $requestAmountInCardHolderBilling,
        // DE007 - Transmission Date and Time
        public readonly ?string $requestTransmitionDateAndTime,
        // DE008 - Amount Cardholder Billing Fee
        public readonly ?string $requestAmountCardholderBillingFee,
        // DE009 - Conversion Rate Settlement
        public readonly ?string $requestConversionRateSettlement,
        // DE010 - Conversion Rate Cardholder Billing
        public readonly ?string $requestConvertionRateCardHolderBilling,
        // DE011 - System Trace Audit Number (STAN)
        public readonly ?string $requestSystemTraceAuditNumber,
        // DE012 - Local Transaction Time
        public readonly ?string $requestLocalTransactionTime,
        // DE013 - Local Transaction Date
        public readonly ?string $requestLocalTransactionDate,
        // DE014 - Expiration Date
        public readonly ?string $requestExpirationDate,
        // DE015 - Settlement Date
        public readonly ?string $requestSettlementDate,
        // DE016 - Conversion Date
        public readonly ?string $requestConversionDate,
        // DE018 - Merchant Category Code (MCC)
        public readonly ?string $requestMcc,
        // DE019 - Acquiring Institution Country Code
        public readonly ?string $requestAcquiringInstitutionCountryCode,
        // DE022 - POS Entry Mode
        public readonly ?string $requestPosEntryMode,
        // DE023 - Card Sequence Number
        public readonly ?string $requestCardSequenceNumber,
        // DE024 - Network International ID
        public readonly ?string $requestNetworkInternationalId,
        // DE025 - POS Condition Code
        public readonly ?string $requestPosConditionCode,
        // DE026 - POS PIN Capture Code
        public readonly ?string $requestPosPinCaptureCode,
        // DE028 - Transaction Fee Amount
        public readonly ?string $requestTransactionFeeAmount,
        // DE029 - Settlement Fee Amount
        public readonly ?string $requestSettlementFeeAmount,
        // DE032 - Acquiring Institution Code
        public readonly ?string $requestAquiringInstitutionCode,
        // DE033 - Forwarding Institution Code
        public readonly ?string $requestForwardingInstitutionCode,
        // DE035 - Track 2 Data
        public readonly ?string $requestTrack2Data,
        // DE036 - Track 3 Data
        public readonly ?string $requestTrack3Data,
        // DE037 - Retrieval Reference Number
        public readonly ?string $requestRetrievalReferenceNumber,
        // DE038 - Authorization ID Response
        public readonly ?string $requestAuthorizationIdResponse,
        // DE039 - Response Code
        public readonly ?string $requestResponseCode,
        // DE041 - Card Acceptor Terminal
        public readonly ?string $requestCardAcceptorTerminal,
        // DE042 - Card Acceptor Identification Code
        public readonly ?string $requestCardAcceptorIdentificationCode,
        // DE043 - Card Acceptor Name/Location
        public readonly ?string $requestCardAcceptorNameLocation,
        // DE045 - Track 1 Data
        public readonly ?string $requestTrack1Data,
        // DE046 - Additional Data ISO
        public readonly ?string $requestAdditionalDataIso,
        // DE047 - Additional Data National
        public readonly ?string $requestAdditionalDataNational,
        // DE048 - Contains PDS in LTV Format
        public readonly ?string $requestContainsPdsInLtvFormat,
        // DE049 - Transaction Currency Code
        public readonly ?string $requestTransactionCurrencyCode,
        // DE050 - Transaction Amount
        public readonly ?string $requestTransactionAmount,
        // DE051 - Currency Code Cardholder Billing
        public readonly ?string $requestCurrencyCodeCardholderBilling,
        // DE052 - Personal Identification Number Data
        public readonly ?string $requestPersonalIdentificationNumberData,
        // DE053 - Security Related Control Information
        public readonly ?string $requestSecurityRelatedControlInformation,
        // DE054 - Additional Amounts
        public readonly ?string $requestAdditionalAmounts,
        // DE055 - ICC System Related Data
        public readonly ?string $requestIccSystemRelatedData,
        // DE056 - Original Data Elements
        public readonly ?string $requestOriginalDataElements,
        // DE058 - Authorization Life Cycle Code
        public readonly ?string $requestAuthorizationLifeCycleCode,
    ) {
    }

    /**
     * Cria DTO a partir do array original_iso8583
     */
    public static function fromArray(array $isoData): self
    {
        return new self(
            requestMti: $isoData['mti'] ?? null,
            requestCardNumber: $isoData['de002'] ?? null,
            requestProcessingCode: $isoData['de003'] ?? null,
            requestTransactionAmountLocal: $isoData['de004'] ?? null,
            requestTransactionAmountReferencia: $isoData['de005'] ?? null,
            requestAmountInCardHolderBilling: $isoData['de006'] ?? null,
            requestTransmitionDateAndTime: $isoData['de007'] ?? null,
            requestAmountCardholderBillingFee: $isoData['de008'] ?? null,
            requestConversionRateSettlement: $isoData['de009'] ?? null,
            requestConvertionRateCardHolderBilling: $isoData['de010'] ?? null,
            requestSystemTraceAuditNumber: $isoData['de011'] ?? null,
            requestLocalTransactionTime: $isoData['de012'] ?? null,
            requestLocalTransactionDate: $isoData['de013'] ?? null,
            requestExpirationDate: $isoData['de014'] ?? null,
            requestSettlementDate: $isoData['de015'] ?? null,
            requestConversionDate: $isoData['de016'] ?? null,
            requestMcc: $isoData['de018'] ?? null,
            requestAcquiringInstitutionCountryCode: $isoData['de019'] ?? null,
            requestPosEntryMode: $isoData['de022'] ?? null,
            requestCardSequenceNumber: $isoData['de023'] ?? null,
            requestNetworkInternationalId: $isoData['de024'] ?? null,
            requestPosConditionCode: $isoData['de025'] ?? null,
            requestPosPinCaptureCode: $isoData['de026'] ?? null,
            requestTransactionFeeAmount: $isoData['de028'] ?? null,
            requestSettlementFeeAmount: $isoData['de029'] ?? null,
            requestAquiringInstitutionCode: $isoData['de032'] ?? null,
            requestForwardingInstitutionCode: $isoData['de033'] ?? null,
            requestTrack2Data: $isoData['de035'] ?? null,
            requestTrack3Data: $isoData['de036'] ?? null,
            requestRetrievalReferenceNumber: $isoData['de037'] ?? null,
            requestAuthorizationIdResponse: $isoData['de038'] ?? null,
            requestResponseCode: $isoData['de039'] ?? null,
            requestCardAcceptorTerminal: $isoData['de041'] ?? null,
            requestCardAcceptorIdentificationCode: $isoData['de042'] ?? null,
            requestCardAcceptorNameLocation: $isoData['de043'] ?? null,
            requestTrack1Data: $isoData['de045'] ?? null,
            requestAdditionalDataIso: $isoData['de046'] ?? null,
            requestAdditionalDataNational: $isoData['de047'] ?? null,
            requestContainsPdsInLtvFormat: $isoData['de048'] ?? null,
            requestTransactionCurrencyCode: $isoData['de049'] ?? null,
            requestTransactionAmount: $isoData['de050'] ?? null,
            requestCurrencyCodeCardholderBilling: $isoData['de051'] ?? null,
            requestPersonalIdentificationNumberData: $isoData['de052'] ?? null,
            requestSecurityRelatedControlInformation: $isoData['de053'] ?? null,
            requestAdditionalAmounts: $isoData['de054'] ?? null,
            requestIccSystemRelatedData: $isoData['de055'] ?? null,
            requestOriginalDataElements: $isoData['de056'] ?? null,
            requestAuthorizationLifeCycleCode: $isoData['de058'] ?? null,
        );
    }
}
