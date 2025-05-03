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
        Schema::create('hpurchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('code')->unique();
            $table->string('status')->default('created');
            $table->string('payment_status')->default('unpaid');
            $table->bigInteger('total');
            $table->bigInteger('paid')->default(0);
            $table->bigInteger('ppn');
            $table->bigInteger('ppn_value');
            $table->bigInteger('grand_total');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('payment_due_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpurchase');
    }
};
