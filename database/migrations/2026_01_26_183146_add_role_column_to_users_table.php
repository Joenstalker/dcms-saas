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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->index();
        });

        // Populate existing roles in a MongoDB-compatible way
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $userId = (string)($user->_id ?? $user->id ?? null);
            if (!$userId) continue;

            if ($user->is_system_admin ?? false) {
                DB::table('users')->where((isset($user->_id) ? '_id' : 'id'), $userId)->update(['role' => 'system_admin']);
                continue;
            }

            // Fetch role IDs for this user
            $roleIds = DB::table('model_has_roles')
                ->where('model_id', $userId)
                ->where('model_type', 'App\Models\User')
                ->pluck('role_id');

            if ($roleIds->isNotEmpty()) {
                $roleNames = DB::table('roles')->whereIn((isset($user->_id) ? '_id' : 'id'), $roleIds)->pluck('name');
                
                $newRole = null;
                if ($roleNames->contains('owner')) $newRole = 'tenant';
                elseif ($roleNames->contains('dentist')) $newRole = 'dentist';
                elseif ($roleNames->contains('assistant')) $newRole = 'assistant';

                if ($newRole) {
                    DB::table('users')->where((isset($user->_id) ? '_id' : 'id'), $userId)->update(['role' => $newRole]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
