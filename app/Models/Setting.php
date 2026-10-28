<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /** @var array<string, ?string>|null */
    protected static ?array $cachedSettings = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            if (static::$cachedSettings === null) {
                static::$cachedSettings = static::pluck('value', 'key')->all();
            }

            return static::$cachedSettings[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        if (static::$cachedSettings !== null) {
            static::$cachedSettings[$key] = $value;
        }
    }
}
