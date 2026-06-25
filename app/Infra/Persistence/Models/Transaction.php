<?php

namespace App\Infra\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'uuid',
        'transaction_uuid',
        'transaction_type',
        'card_uuid',
        'request_mti',
        'request_card_number',
        'request_processing_code',
        'request_transaction_amount_local_original',
        'request_transaction_amount_local',
        'request_transaction_amount_referencia',
        'request_amount_in_card_holder_billing',
        'request_transmition_date_and_time',
        'request_convertion_rate_card_holder_billing',
        'request_system_trace_audit_number',
        'request_local_transaction_time',
        'request_local_transaction_date',
        'request_expiration_date',
        'request_mcc',
        'request_acquiring_institution_country_code',
        'request_pos_entry_mode',
        'request_pos_condition_code',
        'request_aquiring_institution_code',
        'request_retrieval_reference_number',
        'request_authorization_response_code',
        'request_card_acceptor_terminal',
        'request_card_acceptor_identification_code',
        'request_card_acceptor_name_location',
        'request_contains_pds_in_ltv_format',
        'request_transaction_currency_code',
        'request_transaction_amount',
        'request_currency_code_cardholder_billing',
        'request_ps_product_code',
        'response_mti',
        'response_card_number',
        'response_processing_code',
        'response_transaction_amount_local',
        'response_amount_in_card_holder_billing',
        'response_transmition_date_and_time',
        'response_conversion_rate',
        'response_system_trace_audit_number',
        'response_acquiring_institution_country_code',
        'response_pos_condition_code',
        'response_aquiring_institution_code',
        'response_retrieval_reference_number',
        'response_authorization_identification_response',
        'response_code',
        'response_card_acceptor_terminal',
        'response_card_acceptor_identification_code',
        'response_card_acceptor_name_location',
        'response_transaction_currency_code',
        'response_currency_code_cardholder_billing',
    ];

    protected $casts = [
        'request_transaction_amount_local_original' => 'integer',
        'request_transaction_amount_local' => 'integer',
        'request_transaction_amount_referencia' => 'integer',
        'request_amount_in_card_holder_billing' => 'integer',
        'response_transaction_amount_local' => 'integer',
        'response_amount_in_card_holder_billing' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_uuid', 'uuid');
    }
}
