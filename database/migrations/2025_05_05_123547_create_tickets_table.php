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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject',255);
            $table->text('description')->nullable();
            $table->enum('category',['Technical','Billing','General'])->default('General');
            $table->enum('priority',['Low','Medium','High'])->default('High');
            $table->string('attachment')->nullable();
            $table->enum('status',['Open','In-progress','Resolved','Closed'])->default('Open');
            
            $table->index('status');    
            $table->index('category'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
