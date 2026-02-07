<?php

namespace Modules\Supplier\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public static $wrap = null;
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'cnpj'    => $this->cnpj,
            'cep'     => $this->cep,
            'address' => $this->address,
            'status'  => $this->status,
            'sellers' => $this->users
                ->where('type', 'seller')
                ->pluck('id')
                ->values(),
        ];
    }
}
