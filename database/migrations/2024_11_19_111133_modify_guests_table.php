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
        Schema::table('guests', function (Blueprint $table) { 
            // Drop the binary 'id' column 
            $table->dropColumn('id'); 

            // Add the UUID 'id' column 
            $table->uuid('id')->primary(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) { 
            // Drop the UUID 'id' column 
            $table->dropColumn('id'); 
            
            // Add the binary 'id' column 
            $table->binary('id', 16)->primary(); 
        });
    }
};
