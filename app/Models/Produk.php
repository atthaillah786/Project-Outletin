<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'brand_id',
        'produk_name',
        'Price',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }
}