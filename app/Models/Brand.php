<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'brand_id';

    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'franchisor_id',
        'brand_name',
        'description',
        'logo_path',
        'status',
        'verified_by',
        'verified_at',
        'rejection_note',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function franchisor()
    {
        return $this->belongsTo(User::class, 'franchisor_id', 'user_id');
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class, 'brand_id', 'brand_id');
    }

    public function produk()
{
    return $this->hasMany(Produk::class, 'brand_id', 'brand_id');
}
}