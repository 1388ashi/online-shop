<?php

namespace Modules\Product\Http\Requests\Category;

use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\Helpers;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Models\Category;

class storeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:categories,name',
            'parent_id' => ['nullable', 'numeric'],
        ];
    }
    protected function passedValidation(): void
    {
        $category = Category::query()->where('id', $this->parent_id)->exists();
        if ($category == null) {
            throw Helpers::makeValidationException('دسته بندی با این شناسه وجود ندارد');
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
