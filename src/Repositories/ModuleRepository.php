<?php

namespace Glumbo\Generator\Repositories;

use Glumbo\Generator\Module;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\Access\Permission\Permission;
use Illuminate\Support\Str;

/**
 * Class ModuleRepository.
 */
class ModuleRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Module::class;

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->leftjoin('users', 'users.id', '=', 'modules.created_by')
            ->select([
                'modules.id',
                'modules.name',
                'modules.url',
                'modules.view_permission_id',
                'modules.created_by',
                'modules.updated_by',
                'users.first_name as created_by',
            ]);
    }

    /**
     * @param array $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function create(array $input, array $permissions)
    {
        $module = Module::where('name', $input['name'])->first();
        
        if (!$module) {
            $name = $input['model_name'];
            $model = strtolower($name);

            foreach ($permissions as $permission) {
                $perm = [
                    'name'         => $permission,
                    'display_name' => Str::title(str_replace('-', ' ', $permission)).' Permission',
                ];
                //Creating Permission
                $per = Permission::firstOrCreate($perm);
            }

            $mod = [
                'view_permission_id' => "view-$model-permission",
                'name'               => $input['name'],
                'url'                => 'admin.'.Str::plural($model).'.index',
                'module'             => json_encode($input),
                'created_by'         => access()->user()->id,
            ];

            $create = Module::create($mod);

            return $create;
        } else {
            return $module;
        }

        throw new GeneralException('There was some error in creating the Module. Please Try Again.');
    }

    /**
     * @param array $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Module $module, array $permissions)
    {
        if ($module) {
            // Delete data
            Module::destroy($module->id);

            foreach ($permissions as $permission) {
                $per = Permission::whereName($permission)->delete();
            }

            return true;
        } else {
            return $module;
        }

        throw new GeneralException('There was some error in deleting the Module. Please Try Again.');
    }
}
