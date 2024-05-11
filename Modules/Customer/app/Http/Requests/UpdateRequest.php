<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\App\Rules\IranMobile;

class updateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $customerId = $this->route()->parameter('customer')->id;
        return [
            'name' => 'required|min:3|max:120',
            'mobile' => ['required', 'digits:11',new IranMobile],
            'email' => 'nullable|email',
            'national_code' => ['nullable','regex:/^[0-9]{10}$/',Rule::unique('customers')->ignore($customerId)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
