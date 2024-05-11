<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Customer\Models\Address;
use Modules\Invoice\Models\Payment;

class ParchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'driver_name' => ['required',Rule::in(Payment::getAllDriverKeys())],
            'address_id' => ['required','exists:addresses,id'],
        ];
    }
    protected function passedValidation(): void
    {
        $address = Address::query()->where('id', $this->input('address_id'))->first();
        if ($address->customer_id !== Auth::guard('customer-api')->user()->id) {
            throw Helpers::makeValidationException('آدرس متعلق به این کاربر نمیباشد');
        }
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
