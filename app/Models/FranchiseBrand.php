<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseBrand extends Model
{
    protected $table = 'franchise_brands';
    protected $primaryKey = 'franchise_brands_id';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'franchise_id',
        'brand_id',
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
}