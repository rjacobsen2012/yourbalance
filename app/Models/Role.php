<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

/**
 * Class Role
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string key
 * @property Collection|User[] users
 * @property Collection|Permission[] permissions
 * @property Collection|Area[] areas
 *
 * @method static|Builder|QueryBuilder|Role admin()
 * @method static|Builder|QueryBuilder hasKey($key)
 */
class Role extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role')->withTimestamps();
    }

    public function scopeAdmin($query)
    {
        return $query->where('key', 'admin');
    }

    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission_area')
            ->withPivot(['area_id'])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'role_permission_area')
            ->withPivot(['permission_id'])
            ->withTimestamps();
    }

    /**
     * @param Area $area
     * @param Permission $permission
     */
    public function addPermission(Area $area, Permission $permission)
    {
        if (!$this->hasPermission($area, $permission)) {
            $this->permissions()->attach($permission->id, ['area_id' => $area->id]);
        }
    }

    /**
     * @param Area $area
     * @param Permission $permission
     */
    public function removePermission(Area $area, Permission $permission)
    {
        $this->permissions()
            ->where('id', $permission->id)
            ->wherePivot('area_id', $area->id)
            ->detach($permission->id);
    }

    /**
     * @param Area $area
     * @param Permission $permission
     * @return bool|mixed
     */
    public function hasPermission(Area $area, Permission $permission)
    {
        return $this->permissions()->where('key', $permission->key)
            ->wherePivot('area_id', (string) $area->id)->first() ? true : false;
    }

    public function scopeHasKey($query, $key)
    {
        return $query->where('key', $key);
    }
}
