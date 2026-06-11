<?php

namespace Database\Seeders;

use App\Services\PermissionService;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

/**
 * Insere os valores default das permissões configuráveis em system_settings.
 * Só insere se a chave não existir ainda (upsert seguro).
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionService::PERMISSIONS as $permKey => $def) {
            foreach ($def['configurable'] as $role) {
                $key     = "perm.{$role}.{$permKey}";
                $default = $def['defaults'][$role] ?? false;

                SystemSetting::firstOrCreate(
                    ['key' => $key],
                    [
                        'value'       => $default ? '1' : '0',
                        'type'        => 'boolean',
                        'group'       => 'permissions',
                        'label'       => $def['label'] . " ({$role})",
                        'description' => "Permissão configurável para o perfil {$role}",
                    ]
                );
            }
        }

        $this->command->info('Permissões inseridas/verificadas com sucesso.');
    }
}
