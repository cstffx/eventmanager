<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(string $id)
 * @method static findOrFail(string $id)
 * @method static create(array $all)
 * @method static where()
 * @method static lockForUpdate()
 * @method static lock(string $string)
 * @method static dateRange(\Illuminate\Support\Carbon|null $start, \Illuminate\Support\Carbon|null $end)
 * @property integer $capacity
 */
class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "location",
        "date",
        "capacity",
    ];


    public function reservations(): HasMany
    {
        return $this->hasMany(Reservations::class, "event_id");
    }

    /**
     * Return true if the event has reached his capacity.
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->reservations()->count() === $this->capacity;
    }

    /**
     * Returns the available space in the event.
     * @return int
     */
    public function getAvailableSpace(): int
    {
        return $this->capacity - $this->reservations()->count();
    }

    public function scopeDateRange(Builder $query, DateTime $start = null, DateTime $end = null): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('date', [$start, $end]);
        }
        if ($start) {
            return $query->where('date', '>=', $start);
        } else {
            return $query->where('date', '<=', $end);
        }
    }
}
