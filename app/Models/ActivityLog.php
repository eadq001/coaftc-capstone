<?php

namespace App\Models;

use BackedEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class ActivityLog extends Model
{
    private const SENSITIVE_KEYS = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'date_time' => 'datetime',
    ];

    /**
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    public static function record(
        string $action,
        string $model,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null
    ): self {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'old_values' => self::cleanValues($oldValues),
            'new_values' => self::cleanValues($newValues),
            'model' => $model,
            'date_time' => now(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function valuesFor(Model $model): array
    {
        return self::cleanValues($model->getAttributes());
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private static function cleanValues(array $values): array
    {
        return collect(Arr::except($values, self::SENSITIVE_KEYS))
            ->map(fn (mixed $value): mixed => self::cleanValue($value))
            ->all();
    }

    private static function cleanValue(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return self::cleanValues($value);
        }

        return $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
