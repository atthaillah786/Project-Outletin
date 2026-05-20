<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $table = 'verifications';
    protected $primaryKey = 'verification_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'verified_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'superadmin_id',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function superadmin()
    {
        return $this->belongsTo(User::class, 'superadmin_id', 'user_id');
    }
}
