<?php

use App\Services\PermissionService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Insere os valores default das permissões configuráveis em system_settings.
 * Usa INSERT IGNORE / upsert para ser idempotente.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = [];

        foreach (PermissionService::PERMISSIONS as $permKey => $def) {
            foreach ($def['configurable'] as $role) {
                $key     = "perm.{$role}.{$permKey}";
                $default = ($def['defaults'][$role] ?? false) ? '1' : '0';

                $rows[] = [
                    'key'         => $key,
                    'value'       => $default,
                    'type'        => 'boolean',
                    'group'       => 'permissions',
                    'label'       => $def['label'] . " ({$role})",
                    'description' => "Permissão configurável para o perfil {$role}",
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        // Inserir apenas se não existir (idempotente)
        foreach ($rows as $row) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $row['key']],
                $row
            );
        }
    }

    public function down(): void
    {
        DB::table('system_settings')
            ->where('group', 'permissions')
            ->where('key', 'like', 'perm.%')
            ->delete();
    }
};
