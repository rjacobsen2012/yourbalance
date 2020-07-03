<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Place
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection|Balance[] balances
 */
class Label extends Model
{
    protected $guarded = ['id'];

    /**
     * @return HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class);
    }
}
