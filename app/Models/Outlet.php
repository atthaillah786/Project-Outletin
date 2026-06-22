<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = 'outlets';
    protected $primaryKey = 'outlet_id';

    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'franchise_id',
        'brand_id',
        'outlet_name',
        'address',
        'status',
    ];

    public function franchise()
    {
        return $this->belongsTo(User::class, 'franchise_id', 'user_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function produk()
    {
        // Produk is linked to the same brand as the outlet, not directly to outlet_id.
        return $this->hasMany(Produk::class, 'brand_id', 'brand_id');
    }

    public function products()
    {
        return $this->produk();
    }

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class, 'outlet_id', 'outlet_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'outlet_id', 'outlet_id');
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'outlet_id', 'outlet_id');
    }

    // Relasi ke transaksi harian
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'outlet_id', 'outlet_id');
    }

    public function hasDependentRecords()
    {
        return $this->products()->exists()
            || $this->financialReports()->exists()
            || $this->transactions()->exists()
            || $this->materials()->exists()
            || $this->materialRequests()->exists();
    }
}