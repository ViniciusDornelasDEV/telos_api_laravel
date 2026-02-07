<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Supplier;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'supplier_id',
        'reference',
        'name',
        'color',
        'price',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
