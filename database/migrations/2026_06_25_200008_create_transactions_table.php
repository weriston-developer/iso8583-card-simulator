<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->uuid('transaction_uuid')->unique();
            $table->string('transaction_type');

            //reference to card
            $table->uuid('card_uuid')->index();

            // Request fields
            $table->string('request_mti')->nullable();
            $table->string('request_card_number')->nullable();
            $table->string('request_processing_code')->nullable();
            $table->bigInteger('request_transaction_amount_local_original')->nullable();
            $table->bigInteger('request_transaction_amount_local')->nullable();
            $table->bigInteger('request_transaction_amount_referencia')->nullable();
            $table->bigInteger('request_amount_in_card_holder_billing')->nullable();
            $table->string('request_transmition_date_and_time')->nullable();
            $table->string('request_convertion_rate_card_holder_billing')->nullable();
            $table->string('request_system_trace_audit_number')->nullable();
            $table->string('request_local_transaction_time')->nullable();
            $table->string('request_local_transaction_date')->nullable();
            $table->string('request_expiration_date')->nullable();
            $table->string('request_mcc')->nullable();
            $table->string('request_acquiring_institution_country_code')->nullable();
            $table->string('request_pos_entry_mode')->nullable();
            $table->string('request_pos_condition_code')->nullable();
            $table->string('request_aquiring_institution_code')->nullable();
            $table->string('request_retrieval_reference_number')->nullable()->index();
            $table->string('request_authorization_response_code')->nullable();
            $table->string('request_card_acceptor_terminal')->nullable();
            $table->string('request_card_acceptor_identification_code')->nullable();
            $table->string('request_card_acceptor_name_location')->nullable();
            $table->string('request_contains_pds_in_ltv_format')->nullable();
            $table->string('request_transaction_currency_code')->nullable();
            $table->string('request_transaction_amount')->nullable();
            $table->string('request_currency_code_cardholder_billing')->nullable();
            $table->string('request_ps_product_code')->nullable();

            // Response fields
            $table->string('response_mti')->nullable();
            $table->string('response_card_number')->nullable();
            $table->string('response_processing_code')->nullable();
            $table->bigInteger('response_transaction_amount_local')->nullable();
            $table->bigInteger('response_amount_in_card_holder_billing')->nullable();
            $table->string('response_transmition_date_and_time')->nullable();
            $table->string('response_conversion_rate')->nullable();
            $table->string('response_system_trace_audit_number')->nullable();
            $table->string('response_acquiring_institution_country_code')->nullable();
            $table->string('response_pos_condition_code')->nullable();
            $table->string('response_aquiring_institution_code')->nullable();
            $table->string('response_retrieval_reference_number')->nullable()->index();
            $table->string('response_authorization_identification_response')->nullable();
            $table->string('response_code')->nullable()->index();
            $table->string('response_card_acceptor_terminal')->nullable();
            $table->string('response_card_acceptor_identification_code')->nullable();
            $table->string('response_card_acceptor_name_location')->nullable();
            $table->string('response_transaction_currency_code')->nullable();
            $table->string('response_currency_code_cardholder_billing')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
