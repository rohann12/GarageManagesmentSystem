<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRepair extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model
    protected $table = 'order_repairs';

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'order_id',
        'repair_id',
        'user_id',
        'estimated_time',
        'time_taken',
        'Xp',
        'user_Xp'
    ];

    // Define relationships if needed
     // Each OrderRepair belongs to a specific User (Mechanic)
     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }
 
     // Each OrderRepair belongs to a specific Order
     public function order()
     {
         return $this->belongsTo(Order::class, 'order_id');
     }
 
     // Each OrderRepair refers to a specific Repair
     public function repair()
     {
         return $this->belongsTo(Repair::class, 'repair_id');
     }
}
