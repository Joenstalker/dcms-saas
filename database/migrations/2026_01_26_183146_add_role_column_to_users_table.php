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
            $table->string('role')->nullable()->after('is_system_admin')->index();
        });

        // Populate existing roles
        // 1. System Admins
        DB::table('users')->where('is_system_admin', true)->update(['role' => 'system_admin']);

        // 2. Tenant Owners (mapped to 'tenant' role as per user request)
        DB::table('users')
            ->where('is_system_admin', false)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->where('roles.name', 'owner');
            })
            ->update(['role' => 'tenant']);

        // 3. Dentists
        DB::table('users')
            ->where('is_system_admin', false)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->where('roles.name', 'dentist');
            })
            ->update(['role' => 'dentist']);

        // 4. Assistants
        DB::table('users')
            ->where('is_system_admin', false)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->where('roles.name', 'assistant');
            })
            ->update(['role' => 'assistant']);
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
