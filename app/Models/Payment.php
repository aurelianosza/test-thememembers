<?php

namespace App\Models;

use App\Observers\PaymentObserver;
use App\Services\Payments\Interfaces\CanBePaydInterface;
use Error;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([PaymentObserver::class])]
class Payment extends Model implements CanBePaydInterface
{
    use HasFactory,
        SoftDeletes;

    const STATUS_PENDING    = "pending";
    const STATUS_ERROR      = "error";
    const STATUS_SUCCESS    = "success";

    const AVAILABLE_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ERROR,
        self::STATUS_SUCCESS,
    ];

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

    public function logs() : HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function generatePaymentHash() : void
    {
        if($this->payment_hash == '')
        {
            $hash =  md5(serialize([
                "payment_method"    => $this->payment_method,
                "amount"            => $this->amount,
                "buyer_document"    => $this->buyer->document,
                "items"             => $this->products,
                "created_at"        => $this->created_at
            ]));

            $this->update([
                "payment_hash"  => $hash
            ]);
        }
    }

    public function getStatusNameAttribute() : string
    {
        return __(join(".", [
            "models",
            self::class,
            "statuses",
            $this->status
        ]));
    }

    public function getTotalAttribute() : float
    {
        return $this->products
            ->reduce(fn($carry, $current) => $carry + ($current->amount * $current->unitary_price));
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                "amount",
                "unitary_price"
            ]);
    }

    public function paymentData() : array
    {
        return [
            "status"            => $this->status,
            "total"             => $this->total,
            "amount"            => $this->amount,       // Esiste uma diferença entre total e amount, pois o total pode ser um valor, e o comprador pagar com um certo desconto
            "payment_hash"      => $this->payment_hash,
            "processment_date"  => $this->created_at,
            "document"          => $this->buyer->document,
            "email"             => $this->buyer->email
        ];
    }

    // public function paymentData() : array;
    public function setStatus(string $status) : void
    {
        if(!in_array($status, self::AVAILABLE_STATUSES))
        {
            throw new Error("Cant set status {$status} to payment.");
        }

        $this->update([
            "status"    => $status
        ]);
    }
    
    public function getStatus() : string
    {
        return $this->status;
    }

    public function getStatusName() : string
    {
        return $this->status_name;
    }
    public function logPayment(string $logData) : void
    {
        $this->logs()
            ->create([
                "message"   => $logData
            ]);
    }
}
