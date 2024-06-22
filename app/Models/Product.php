<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = "products";

    protected $fillable = [
        "code",
        "name", 
        "description",
        "price"
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function logs() : HasMany
    {
        return $this->hasMany(ProductUpdate::class);    
    }

    public function getPriceAttribute($value) : float {
        
        return round($value, 2);

    }

    public function logAttributes()
    {
        return [
            "code"          => $this->code,
            "name"          => $this->name, 
            "description"   => $this->description,
            "price"         => $this->price
        ];
    }
}
