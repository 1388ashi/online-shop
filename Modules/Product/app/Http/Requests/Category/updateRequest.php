<?php

namespace Modules\Product\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\App\Helpers\Helpers;
use Modules\Product\Models\Category;

class updateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route()->parameter('category')->id;
        return [
            'name' => [Rule::unique('categories')->ignore($categoryId)],
        ];
    }
    protected function passedValidation(): void
    {
         if(filled($this->parent_id)){
            $category = Category::query()->where('id', $this->parent_id)->exists();
            if ($category == null) {
                throw Helpers::makeValidationException('دسته بندی با این شناسه وجود ندارد');
            }
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
