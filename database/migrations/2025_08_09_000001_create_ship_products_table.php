<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ship_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ship_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
            $table->index(['ship_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ship_products');
    }
};


