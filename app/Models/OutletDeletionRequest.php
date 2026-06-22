<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OutletDeletionRequest extends Model
{
    protected $table = 'outlet_deletion_requests';
    protected $primaryKey = 'outlet_deletion_request_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'outlet_id',
        'franchise_id',
        'brand_id',
        'outlet_name',
        'reason',
        'status',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
    }

    public function franchise()
    {
        return $this->belongsTo(User::class, 'franchise_id', 'user_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
