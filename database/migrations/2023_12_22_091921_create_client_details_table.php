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
        Schema::create('client_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('client_name');
            $table->string("company_name");
            $table->string("primary_email");
            $table->string("sec_email");
            $table->string("phone");
            $table->string("sec_phone");
            $table->string("photo");
            $table->integer("approve");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_details');
    }
};
