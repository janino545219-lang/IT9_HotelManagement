<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('walkin_id')->nullable()->after('reservation_id');
            $table->foreign('walkin_id')->references('walkin_id')->on('walk_ins')->onDelete('cascade');
        });
        
        // Drop foreign keys
        DB::statement('ALTER TABLE invoices DROP FOREIGN KEY invoices_reservation_id_foreign');
        DB::statement('ALTER TABLE invoices DROP FOREIGN KEY invoices_guest_id_foreign');
        
        // Make columns nullable
        DB::statement('ALTER TABLE invoices MODIFY reservation_id CHAR(36) NULL');
        DB::statement('ALTER TABLE invoices MODIFY guest_id CHAR(36) NULL');
        
        // Add foreign keys back
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT invoices_reservation_id_foreign FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT invoices_guest_id_foreign FOREIGN KEY (guest_id) REFERENCES guests(guest_id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['walkin_id']);
            $table->dropColumn('walkin_id');
        });
        
        DB::statement('ALTER TABLE invoices DROP FOREIGN KEY invoices_reservation_id_foreign');
        DB::statement('ALTER TABLE invoices DROP FOREIGN KEY invoices_guest_id_foreign');
        
        DB::statement('ALTER TABLE invoices MODIFY reservation_id CHAR(36) NOT NULL');
        DB::statement('ALTER TABLE invoices MODIFY guest_id CHAR(36) NOT NULL');
        
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT invoices_reservation_id_foreign FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE invoices ADD CONSTRAINT invoices_guest_id_foreign FOREIGN KEY (guest_id) REFERENCES guests(guest_id) ON DELETE CASCADE');
    }
};
