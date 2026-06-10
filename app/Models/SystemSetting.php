<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    // Cache em memória por request
    protected static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (!isset(static::$cache[$key])) {
            $setting = static::where('key', $key)->first();
            static::$cache[$key] = $setting?->castValue() ?? $default;
        }
        return static::$cache[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => (string) $value]);
        static::$cache[$key] = $value;
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value);
        }
    }

    public static function clearCache(): void
    {
        static::$cache = [];
    }

    public static function group(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)->orderBy('id')->get();
    }

    public function castValue(): mixed
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            default   => $this->value,
        };
    }
}
