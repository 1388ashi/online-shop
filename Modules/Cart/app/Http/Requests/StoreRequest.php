<?php

namespace Modules\Cart\Http\Requests;

use App\Rules\CheckInventory;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Cart\Models\Cart;
use Modules\Core\App\Helpers\Helpers;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => ['required', 'integer', 'min:1', new CheckInventory($this->product_id)],

        ];
    }
    public function passedValidation(): void{
        $productId = $this->product_id;
        $cart = Cart::where('product_id',$productId)->exists();
        
        if($cart){
            throw Helpers::makeValidationException('این محصول در سبد خرید موجود است');
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
