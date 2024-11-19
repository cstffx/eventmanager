<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(mixed $validated)
 * @method static where(string $string, string $id)
 * @property string $username
 * @property string $phone_number
 * @property string $email
 * @property integer $event_id
 */
class Reservations extends Model
{
    use HasFactory;

    protected $fillable = [
        "username",
        "phone_number",
        "email",
        "event_id",
    ];

    /**
     * @return BelongsTo
     */
    function event(): BelongsTo
    {
        return $this->belongsTo(Events::class);
    }
}
