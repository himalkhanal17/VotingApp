<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('votes', function (Blueprint $table) {
        $table->dropForeign(['voter_id']);
        $table->foreignId('voter_id')->nullable()->change();
        $table->foreign('voter_id')->references('id')->on('voters')->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
