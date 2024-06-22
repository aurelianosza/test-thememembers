<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING    = 'pending';
    const STATUS_ERROR      = 'pending';
    const STATUS_COMPLETED  = 'pending';

    const PIX           = "pix";
    const BOLETO        = "boleto";
    const CREDIT_CARD   = "credit_card";

    const AVAILABLE_PAYMENT_METHODS = [
        self::PIX                       => "pix",
        self::BOLETO                    => "boleto",
        self::CREDIT_CARD               => "credit_card",
    ];

    protected $table = "payments";

    protected $fillable = [
        "payment_hash",  
        "payment_method",
        "status",
        "amount",
        "buyer_id",
        "product_id",
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                "amount",
                "unitary_price"
            ]);
    }
}
