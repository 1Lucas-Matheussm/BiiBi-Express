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

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['pendente', 'em_andamento', 'entregue', 'cancelado'])->default('pendente');
            $table->string('origin_address');
            $table->string('destination_address');

            $table->decimal('total_price', 8, 2);
            $table->enum('payment_method', ['pix', 'cartao', 'dinheiro']);
            $table->text('observations')->nullable();

            $table->float('height')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();

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
