<?php

namespace Modules\Customer\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\App\Rules\IranMobile;

class storeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'mobile' => ['required', 'digits:11',new IranMobile],
            'status' => 'required|in:available,unavailable,draft',
            'address' => 'required|string',
            'postal_code' => 'integer|required|min:1',
            'city_id' => 'required|integer|exists:cities,id',
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
