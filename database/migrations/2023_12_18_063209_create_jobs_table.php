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
            $table->string("client_id");
            $table->string("job_id");
            $table->string("date");
            $table->integer("multidrop");
            $table->json("job_location_data");
            $table->string("vehicle");
            $table->string("status");
            $table->string("pod")->nullable();
            $table->string("invoice_status");
            $table->json("invoice")->nullable();
            $table->string("eta")->nullable();
            $table->json("update")->nullable();
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
