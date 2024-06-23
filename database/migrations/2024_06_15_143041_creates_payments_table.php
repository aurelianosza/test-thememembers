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
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->string("payment_hash");
            $table->string("payment_method");
            $table->string("status");
            $table->decimal("amount", total: 8, places: 2);
            $table->foreignId("buyer_id")
                ->references("id")
                ->on("buyers");
            $table->decimal("change", total: 8, places: 2)
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("payments");
    }
};
