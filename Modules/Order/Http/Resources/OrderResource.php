<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Http\Resources\OrderItemResource;

class OrderResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'supplier'     => $this->supplier
            ? [
                'id'   => $this->supplier->id,
                'name' => $this->supplier->name,
              ]
            : null,
            'supplier_id'  => $this->supplier_id,
            'user_id'      => $this->user_id,
            'date'         => $this->date?->format('Y-m-d'),
            'observation'  => $this->observation,
            'status'       => $this->status,
            'is_editable'  => $this->isEditable(),
            'total'        => $this->total, 2,
            'items'        => OrderItemResource::collection(
                                $this->whenLoaded('items')
                              ),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
