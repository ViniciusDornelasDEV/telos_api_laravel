<?php

namespace Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Supplier\Models\Supplier;

class InsertSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('insert', Supplier::class);
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'cnpj'    => 'required|string|size:18|unique:suppliers,cnpj',
            'cep'     => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'status'  => 'required|boolean',
        ];
    }
}
