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

    public function financialReports()
    {
        return $this->hasMany(FinancialReport::class, 'outlet_id', 'outlet_id');
    }
    
}