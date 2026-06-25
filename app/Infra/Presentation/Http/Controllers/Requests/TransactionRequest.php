<?php

namespace App\Infra\Presentation\Http\Controllers\Requests;

use App\Application\DTOs\Inputs\Info\AmountInput;
use App\Application\DTOs\Inputs\Info\AuthorizationInput;
use App\Application\DTOs\Inputs\Info\CardInput;
use App\Application\DTOs\Inputs\Info\EstablishmentInput;
use App\Application\DTOs\Inputs\Info\TransactionMessageInput;
use App\Application\DTOs\Inputs\TransactionInput;
use App\Domain\VOs\MoneyVO;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
    * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'transaction_uuid' => ['required', 'string'],
            'transaction_type' => ['required', 'string'],
            'ps_product_code' => ['required', 'string'],
            'ps_product_name' => ['required', 'string'],
            'country_code' => ['required', 'string', 'size:2'],
            'pre_authorization' => ['required', 'string'],
            'entry_mode' => ['required', 'string'],

            'authorization' => ['required', 'array'],
            'authorization.code' => ['required', 'string'],
            'authorization.description' => ['required', 'string'],

            'internacional' => ['required', 'boolean'],
            'fees' => ['nullable', 'array'],
            'brand' => ['required', 'string'],

            'establishment_input' => ['required', 'array'],
            'establishment_input.mcc' => ['required', 'string'],
            'establishment_input.name' => ['required', 'string'],
            'establishment_input.city' => ['required', 'string'],
            'establishment_input.country_code' => ['required', 'string', 'size:2'],
            'establishment_input.address' => ['required', 'string'],
            'establishment_input.zip_code' => ['required', 'string'],
            'establishment_input.pat' => ['required', 'boolean'],

            'amount' => ['required', 'array'],
            'amount.total_amount' => ['required', 'integer', 'min:0'],
            'amount.total_currency_code' => ['required', 'string', 'size:3'],
            'amount.original_amount' => ['required', 'integer', 'min:0'],
            'amount.original_currency_code' => ['required', 'string', 'size:3'],

            'card_input' => ['required', 'array'],
            'card_input.card_id' => ['required', 'integer', 'min:1'],
            'card_input.card_last_four_digits' => ['required', 'string', 'size:4'],
            'card_input.card_input' => ['required', 'string'],
            'card_input.bin' => ['required', 'string'],

            'iso8583_message_input' => ['required', 'array'],
            'force_accept' => ['required', 'boolean'],
            'platform' => ['required', 'string'],
        ];
    }

    public function toInput(): TransactionInput
    {
        /** @var array<string, mixed> $data */
        $data = $this->validated();

        return new TransactionInput(
            transactionUuid: (string) $data['transaction_uuid'],
            transactionType: (string) $data['transaction_type'],
            psProductCode: (string) $data['ps_product_code'],
            psProductName: (string) $data['ps_product_name'],
            countryCode: (string) $data['country_code'],
            preAuthorization: (string) $data['pre_authorization'],
            entryMode: (string) $data['entry_mode'],
            authorization: new AuthorizationInput(
                code: (string) data_get($data, 'authorization.code'),
                description: (string) data_get($data, 'authorization.description')
            ),
            internacional: (bool) $data['internacional'],
            fees: (array) $data['fees'],
            brand: (string) $data['brand'],
            establishmentInput: new EstablishmentInput(
                mcc: (string) data_get($data, 'establishment_input.mcc'),
                name: (string) data_get($data, 'establishment_input.name'),
                city: (string) data_get($data, 'establishment_input.city'),
                countryCode: (string) data_get($data, 'establishment_input.country_code'),
                address: (string) data_get($data, 'establishment_input.address'),
                zipCode: (string) data_get($data, 'establishment_input.zip_code'),
                pat: (bool) data_get($data, 'establishment_input.pat')
            ),
            amount: new AmountInput(
                totalAmount: MoneyVO::fromCents((int) data_get($data, 'amount.total_amount')),
                totalCurrencyCode: (string) data_get($data, 'amount.total_currency_code'),
                originalAmount: MoneyVO::fromCents((int) data_get($data, 'amount.original_amount')),
                originalCurrencyCode: (string) data_get($data, 'amount.original_currency_code')
            ),
            cardInput: new CardInput(
                cardUuid: (string) data_get($data, 'card_input.card_uuid'),
                cardLastFourDigits: (string) data_get($data, 'card_input.card_last_four_digits'),
                cardInput: (string) data_get($data, 'card_input.card_input'),
                bin: (string) data_get($data, 'card_input.bin')
            ),
            iso8583MessageInput: TransactionMessageInput::fromArray((array) $data['iso8583_message_input']),
            forceAccept: (bool) $data['force_accept'],
            platform: (string) $data['platform'],
        );
    }
}
