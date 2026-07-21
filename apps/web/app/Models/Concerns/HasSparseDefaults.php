<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSparseDefaults
{
    /** @return array<string, mixed> */
    abstract protected function sparseDefaults(): array;

    public static function bootHasSparseDefaults(): void
    {
        static::saving(function (self $model): void {
            foreach ($model->sparseDefaults() as $field => $default) {
                if (array_key_exists($field, $model->getAttributes()) && $model->getAttributes()[$field] === $default) {
                    $model->unset($field);
                }
            }
        });
    }

    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);
        $defaults = $this->sparseDefaults();

        return $value === null && is_string($key) && array_key_exists($key, $defaults)
            ? $defaults[$key]
            : $value;
    }

    public function scopeWhereSparseDefault(Builder $query, string $field, mixed $default): Builder
    {
        return $query->where(function (Builder $query) use ($field, $default): void {
            $query->where($field, $default)->orWhereNull($field);
        });
    }
}
