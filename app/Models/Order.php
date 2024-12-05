<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_name',
        'email',
        'vehicle_no',
        'start_time',
        'estimated_completed',
        'estimated_cost',
        'repairs',
        'status',
        'priority',
        'customer_id',
        'time_taken',
        'assigned_to'
    ];
}