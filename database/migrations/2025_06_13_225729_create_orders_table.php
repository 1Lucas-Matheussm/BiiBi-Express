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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('delivery_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('users')->onDelete('set null');

            $table->integer('status')->default('1'); // 1: pendente, 2: em_andamento, 3: entregue, 4: cancelado
            $table->string('origin_address');
            $table->string('destination_address');

            $table->decimal('total_price', 8, 2);
            $table->enum('payment_method', ['pix', 'cartao', 'dinheiro']);
            $table->text('observations');

            $table->integer('package_size'); // 1: pequeno, 2: medio, 3: grande
            $table->boolean('fragile');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
