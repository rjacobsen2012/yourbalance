<?php

use App\Models\Area;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('roles') as $roleData) {
            /** @var Role $role */
            $role = Role::firstOrCreate([
                'name' => $roleData['name'],
                'key' => $roleData['key'],
            ]);

            foreach ($roleData['areas'] as $areaKey => $permissionKeys) {
                /** @var Area $area */
                $area = Area::hasKey($areaKey)->first();

                foreach ($permissionKeys as $permissionKey) {
                    /** @var Permission $permission */
                    $permission = Permission::hasKey($permissionKey)->first();

                    if (!$role->hasPermission($area, $permission)) {
                        $role->permissions()->attach($permission->id, ['area_id' => $area->id]);
                    }
                }
            }
        }
    }
}
