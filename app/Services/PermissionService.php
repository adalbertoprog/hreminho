<?php

namespace App\Services;

use App\Models\SystemSetting;

/**
 * Serviço de permissões configuráveis por perfil.
 *
 * Cada permissão tem um valor default (o comportamento actual do sistema).
 * O admin pode sobrepor qualquer permissão marcada como configurável em Settings.
 *
 * Chaves em system_settings: "perm.{role}.{permission}"  (ex: "perm.manager.view_employees")
 * Valor: "1" (permitido) ou "0" (negado)
 *
 * O perfil "admin" tem sempre acesso total e não é configurável.
 * O perfil "employee" só tem acesso ao portal e não é configurável.
 */
class PermissionService
{
    /**
     * Permissões e os seus defaults por perfil.
     *
     * Estrutura: [ 'permission_key' => [ 'roles' => ['admin'=>true, 'hr'=>bool, 'manager'=>bool, 'employee'=>bool], 'configurable' => ['hr', 'manager'] ] ]
     *
     * configurable: perfis cujo valor pode ser alterado em Settings (admin e employee nunca são configuráveis)
     */
    public const PERMISSIONS = [
        // ── Funcionários ──────────────────────────────────────────────
        'view_employees' => [
            'label'        => 'Funcionários — Ver lista',
            'group'        => 'employees',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['manager'],
        ],
        'edit_employees' => [
            'label'        => 'Funcionários — Criar / Editar',
            'group'        => 'employees',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['manager'],
        ],
        'delete_employees' => [
            'label'        => 'Funcionários — Eliminar',
            'group'        => 'employees',
            'defaults'     => ['admin' => true,  'hr' => false, 'manager' => false, 'employee' => false],
            'configurable' => ['hr'],
        ],

        // ── Presenças ─────────────────────────────────────────────────
        'view_attendances' => [
            'label'        => 'Presenças — Ver (âmbito dept/sector)',
            'group'        => 'attendances',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => true,  'employee' => false],
            'configurable' => ['manager'],
        ],
        'manage_attendances' => [
            'label'        => 'Presenças — Registar / Editar',
            'group'        => 'attendances',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => true,  'employee' => false],
            'configurable' => ['hr', 'manager'],
        ],

        // ── Licenças ──────────────────────────────────────────────────
        'approve_leaves' => [
            'label'        => 'Licenças — Aprovar / Rejeitar',
            'group'        => 'leaves',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => true,  'employee' => false],
            'configurable' => ['hr', 'manager'],
        ],
        'view_all_leaves' => [
            'label'        => 'Licenças — Ver todos os pedidos',
            'group'        => 'leaves',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['manager'],
        ],

        // ── Obras / Equipas / Viaturas ────────────────────────────────
        'view_projects' => [
            'label'        => 'Obras — Ver (gestão completa)',
            'group'        => 'projects',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => true,  'employee' => false],
            'configurable' => ['hr', 'manager'],
        ],
        'manage_projects' => [
            'label'        => 'Obras — Criar / Editar / Eliminar',
            'group'        => 'projects',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['hr', 'manager'],
        ],

        // ── Relatórios ────────────────────────────────────────────────
        'view_reports' => [
            'label'        => 'Relatórios — Ver / Exportar',
            'group'        => 'reports',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['manager'],
        ],

        // ── Formações ─────────────────────────────────────────────────
        'manage_trainings' => [
            'label'        => 'Formações — Gerir / Inscrever',
            'group'        => 'trainings',
            'defaults'     => ['admin' => true,  'hr' => true,  'manager' => false, 'employee' => false],
            'configurable' => ['manager'],
        ],
    ];

    /** Roles que nunca são configuráveis (sempre hardcoded) */
    private const FIXED_ROLES = ['admin', 'employee'];

    /** Cache em memória para o request actual */
    private static array $resolved = [];

    /**
     * Verifica se o perfil tem uma permissão.
     * Admin é sempre true; employee é sempre o default (sem override).
     */
    public static function allows(string $role, string $permission): bool
    {
        if ($role === 'admin') return true;

        $cacheKey = "{$role}.{$permission}";
        if (isset(static::$resolved[$cacheKey])) {
            return static::$resolved[$cacheKey];
        }

        $def = static::PERMISSIONS[$permission] ?? null;
        if (!$def) {
            return static::$resolved[$cacheKey] = false;
        }

        $default = $def['defaults'][$role] ?? false;

        // Roles fixos não têm override
        if (in_array($role, self::FIXED_ROLES)) {
            return static::$resolved[$cacheKey] = $default;
        }

        // Verificar se é configurável
        if (!in_array($role, $def['configurable'] ?? [])) {
            return static::$resolved[$cacheKey] = $default;
        }

        // Ler override de system_settings
        $settingKey = "perm.{$role}.{$permission}";
        $stored = SystemSetting::where('key', $settingKey)->value('value');

        if ($stored === null) {
            return static::$resolved[$cacheKey] = $default;
        }

        return static::$resolved[$cacheKey] = filter_var($stored, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Limpa o cache (útil após gravar novas permissões).
     */
    public static function clearCache(): void
    {
        static::$resolved = [];
        SystemSetting::clearCache();
    }

    /**
     * Devolve toda a matriz de permissões com os valores actuais para a UI.
     * Formato: [ permission_key => [ 'label', 'group', 'values' => ['hr'=>bool, 'manager'=>bool], 'configurable' => [...] ] ]
     */
    public static function matrix(): array
    {
        $result = [];
        foreach (static::PERMISSIONS as $key => $def) {
            $values = [];
            foreach (['hr', 'manager'] as $role) {
                $values[$role] = static::allows($role, $key);
            }
            $result[$key] = [
                'label'        => $def['label'],
                'group'        => $def['group'],
                'values'       => $values,
                'configurable' => $def['configurable'],
                'defaults'     => array_filter(
                    $def['defaults'],
                    fn($r) => in_array($r, ['hr', 'manager']),
                    ARRAY_FILTER_USE_KEY
                ),
            ];
        }
        return $result;
    }

    /**
     * Grava overrides de permissões a partir de um array ['role.permission' => bool].
     * Apenas aceita combinações role+permission válidas e configuráveis.
     */
    public static function save(array $data): void
    {
        foreach ($data as $dotKey => $value) {
            [$role, $permission] = array_pad(explode('.', $dotKey, 2), 2, '');
            if (!$role || !$permission) continue;
            if (in_array($role, self::FIXED_ROLES)) continue;

            $def = static::PERMISSIONS[$permission] ?? null;
            if (!$def) continue;
            if (!in_array($role, $def['configurable'] ?? [])) continue;

            $settingKey = "perm.{$role}.{$permission}";
            $boolVal = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

            SystemSetting::updateOrCreate(
                ['key' => $settingKey],
                [
                    'value'       => $boolVal,
                    'type'        => 'boolean',
                    'group'       => 'permissions',
                    'label'       => $def['label'] . " ({$role})",
                    'description' => "Permissão configurável para o perfil {$role}",
                ]
            );
        }

        static::clearCache();
    }
}
