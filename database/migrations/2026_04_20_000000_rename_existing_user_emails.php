<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Replace all existing emails in the users table with unique dummy emails
        // Format: old_{id}@deleted.com
        // Exclude users with 'admin' or 'super admin' roles to avoid breaking access
        DB::table('users')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->whereIn('roles.name', ['admin', 'super admin']);
            })
            ->update([
                'email' => DB::raw("CONCAT('old_', id, '@deleted.com')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this is not easily possible without a backup of original emails
        // But for migration consistency, we can leave it empty or note it.
    }
};
