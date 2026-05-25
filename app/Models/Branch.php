<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class Branch extends Model
{
    //
    protected $fillable = [
        'city',
        'name',
        'address',
        'is_active',
    ];

    public function inventories() 
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
