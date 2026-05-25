<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'category',
        'price',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
