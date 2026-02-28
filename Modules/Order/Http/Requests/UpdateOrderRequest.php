<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'                 => ['required', 'date'],
            'products'             => ['required', 'array', 'min:1'],
            'products.*.id'        => ['required', 'exists:products,id'],
            'products.*.unitPrice' => ['required', 'numeric'],
            'products.*.quantity'  => ['required', 'integer'],
            'status'               => ['nullable', 'in:Pendente,Concluído,Cancelado'],
            'observation'          => ['nullable', 'string'],
        ];
    }
}
