<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Place
 * @package App\Models
 *
 * @property int id
 * @property float amount
 * @property Carbon date
 * @property Carbon day
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User user
 * @property Label label
 */
class Balance extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'date',
    ];

    protected $appends = [
        'day'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function getDayAttribute()
    {
        return $this->date->format('Y-m-d');
    }
}
