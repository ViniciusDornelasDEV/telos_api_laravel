<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Models\OrderItem;
use Modules\User\Models\User;
use Modules\Supplier\Models\Supplier;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'supplier_id',
        'user_id',
        'date',
        'observation',
        'status',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isEditable(): bool
    {
        return $this->status === 'Pendente';
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
