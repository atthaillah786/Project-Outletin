<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $table = 'financial_reports';
    protected $primaryKey = 'financial_id';

    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'outlet_id',
        'report_date',
        'total_items',
        'total_income',
        'total_expense',
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_items' => 'integer',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
    }
}