<?php

namespace Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;
use Modules\Product\Models\Product;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'cnpj',
        'cep',
        'address',
        'status',
    ];

    /*
     * Relacionamentos (para depois)
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'supplier_user'
        );
    }

    public function products()
    {
        return $this->hasMany(
            Product::class
        );
    }
}
