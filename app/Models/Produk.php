<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

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

    public function materials()
    {
        return $this->hasMany(Material::class, 'produk_id', 'produk_id');
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'produk_id', 'produk_id');
    }
}
