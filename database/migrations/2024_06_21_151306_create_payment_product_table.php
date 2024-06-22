<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_product', function (Blueprint $table) {

            $table
                ->foreignId("product_id")
                ->references("id")
                ->on("products");

            $table
                ->foreignId("payment_id")
                ->references("id")
                ->on("payments");
            
            $table->decimal("amount", total: 10, places: 3);

            $table->decimal("unitary_price", total: 10, places:4);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_product');
    }
};
