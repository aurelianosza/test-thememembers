<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUpdate extends Model
{
    use HasFactory;

    protected $table = "product_log_update";

    public $fillable = [
        "product_id",
        "product_update_payload"
    ];

    public $casts = [
        "product_update_payload"    => "array"
    ];
}
