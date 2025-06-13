<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_person_id',
        'company_id',
        'status',
        'origin_address',
        'destination_address',
        'total_price',
        'payment_method',
        'observations',
        'altura',
        'comprimento',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
