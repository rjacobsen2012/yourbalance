<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

/**
 * Class Permission
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string key
 * @property Collection|Role[] roles
 *
 * @method static|Builder|QueryBuilder hasKey($key)
 */
class Permission extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission_area')
            ->withPivot('area_id')
            ->withTimestamps();
    }

    public function scopeHasKey($query, $key)
    {
        return $query->where('key', $key);
    }
}
