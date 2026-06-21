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
    const UPDATED_AT = null; // Menandakan tabel ini tidak menggunakan kolom updated_at

    // GUNANYA: Memastikan 'logo_path' aman dimasukkan ke database saat user mengunggah foto brand
    protected $fillable = [
        'franchisor_id',
        'brand_name',
        'description',
        'logo_path', // <-- Ini sudah aman terpasang di sini
        'status',
        'verified_by',
        'verified_at',
        'rejection_note',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relasi ke User pemilik brand
    public function franchisor()
    {
        return $this->belongsTo(User::class, 'franchisor_id', 'user_id');
    }

    // Relasi ke cabang-cabang outlet
    public function outlets()
    {
        return $this->hasMany(Outlet::class, 'brand_id', 'brand_id');
    }

    // Relasi ke katalog produk milik brand ini
    public function produk()
    {
        return $this->hasMany(Produk::class, 'brand_id', 'brand_id');
    }
}