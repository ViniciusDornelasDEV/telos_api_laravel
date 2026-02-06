<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'product_id'  => $this->product_id,
            'unit_price'  => number_format($this->unit_price, 2, ',', '.'),
            'quantity'    => $this->quantity,
            'total'       => number_format($this->total, 2, ',', '.'),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
