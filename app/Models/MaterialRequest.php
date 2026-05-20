<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $table = 'material_requests';
    protected $primaryKey = 'material_request_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'request_date';
    const UPDATED_AT = null;

    protected $fillable = [
        'franchise_id',
        'outlet_id',
        'produk_id',
        'quantity_requested',
        'status',
        'request_date',
    ];

    protected $casts = [
        'request_date' => 'datetime',
    ];

    public function franchise()
    {
        return $this->belongsTo(User::class, 'franchise_id', 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
