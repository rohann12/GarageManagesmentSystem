<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'total_price',
        'labor_charges',
        'status',
    ];

    public function parts()
    {
        return $this->belongsToMany(Part::class)->withPivot('quantity', 'price');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
