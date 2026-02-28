<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Product\Models\Product;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', Product::class);
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'reference'   => 'nullable|string|max:255',
            'name'        => 'required|string|max:255',
            'color'       => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|boolean',
        ];
    }
}
