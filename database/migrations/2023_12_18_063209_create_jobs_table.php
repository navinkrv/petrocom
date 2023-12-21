<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("user_id");
            $table->string("job_id");
            $table->string("date");
            $table->integer("multidrop");
            $table->json("job_location_data");
            $table->string("status");
            $table->string("pod");
            $table->string("invoice_status");
            $table->json("invoice");
            $table->string("eta");
            $table->json("update");
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
