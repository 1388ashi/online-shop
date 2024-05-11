<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Product\Models\Product;


class CheckInventory implements Rule
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function passes($attribute, $value)
    {
        $product = Product::find($this->productId);
        if ($product->store->balance < $value) {
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'موجودی انبار کافی نیست';
    }
}
