<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walk_ins', function (Blueprint $table) {
            $table->uuid('room_id')->nullable()->after('employee_id');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('walk_ins', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
};
