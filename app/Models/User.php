<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property int id
 * @property string first_name
 * @property string last_name
 * @property string name
 * @property string email
 * @property Collection|Role[] roles
 * @property string avatar
 * @property string password
 * @property string provider
 * @property string provider_id
 * @property Collection|Balance[] balances
 *
 * @method static|Builder|QueryBuilder|User admin
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = ['id'];

    protected $appends = [
        'is_admin',
        'name',
        'gravatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getGravatarAttribute()
    {
        return md5($this->email);
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class)->orderBy('date', 'desc');
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->hasAdminAccess('admin', 'read');
    }

    public function scopeAdmin($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('key', 'admin');
        });
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->roles->where('key', 'admin')->first() ? true : false;
    }

    /**
     * @param string|Area $area
     * @param string|Permission $permission
     * @return bool
     */
    public function hasAdminAccess($area, $permission)
    {
        foreach ($this->roles as $role) {
            /** @var Area $area */
            $area = $area instanceof Area ? $area : Area::hasKey($area)->first();

            /** @var Permission $permission */
            $permission = $permission instanceof Permission ? $permission : Permission::hasKey($permission)->first();

            if ($role->hasPermission($area, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
