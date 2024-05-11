<?php

namespace Modules\Product\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class storeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
                'title' => 'required|unique:products,title',
                'status' => 'required|in:available,unavailable,draft',
                'description' => 'required|string',
                'quantity' => 'integer|required|min:0',
                'price' => 'integer|required',
                'discount' => 'nullable|integer|min:1',
                'discount_type' => 'required|in:percent,flat',
                'image' => 'required',
                'galleries.*' => 'required',
                'galleries' => 'required|array',  
                'category_id' => 'required|integer|exists:categories,id',
                'specifications.*' => 'required',
                'specifications' => 'required|array',  
                'specifications.id' => 'integer|exists:specifications,id',  
                'specifications.value' => 'string',  
        ];
    }

    public function validated($key = null, $default = null) {
        $validated = parent::validated();
        unset(
            $validated['specifications'],
            $validated['image'],
            $validated['galleries']
        );
    
        return $validated;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
