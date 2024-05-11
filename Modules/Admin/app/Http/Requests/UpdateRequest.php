<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:100',
                Rule::unique('admins')->ignore($this->route('admin')->id)
            ],
            'mobile' => [
                'required',
                'max:12',
                Rule::unique('admins')->ignore($this->route('admin')->id)
            ],
            'label' => ['nullable', 'string', 'max:191'],
            // 'permissions' => ['nullable', 'array'],
            // 'permissions.*' => ['required', 'string', Rule::exists('permissions', 'name')],
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
