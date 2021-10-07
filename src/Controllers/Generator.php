<?php

namespace Glumbo\Generator\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Generator
{
    /**
     * Original Name
     */
    protected $originalName;

    /**
     * Module Name.
     */
    protected $module;

    /**
     * Files Object.
     */
    protected $files;

    /**
     * Directory Name.
     */
    protected $directory;

    /**
     * ----------------------------------------------------------------------------------------------
     * Model Related Files
     * -----------------------------------------------------------------------------------------------
     * 1. Model Name
     * 2. Attribute Name
     * 3. Relationship Name
     * 4. Attribute Namespace
     * 5. Relationship Namespace
     * 6. Traits directory
     * 7. Model Namespace.
     */
    protected $model;
    protected $attribute;
    protected $relationship;
    protected $attribute_namespace;
    protected $relationship_namespace;
    protected $trait_directory = 'Traits';
    protected $model_namespace = 'App\\Models\\';

    /**
     * Responses
     * 1. EditResponse Name
     * 2. CreateResponse Name
     * 3. EditResponse Namespace
     * 4. CreateResponse Namespace
     * 5. Responses Namespace
     */
    protected $edit_response = 'EditResponse';
    protected $create_response = 'CreateResponse';
    protected $edit_response_namespace;
    protected $create_response_namespace;
    protected $responses_namespace = 'App\\Http\\Responses\\';

    /**
     * Controllers
     * 1. Controlller Name
     * 2. Table Controller Name
     * 3. Controller Namespace
     * 4. Table Controller Namespace.
     */
    protected $controller;
    protected $table_controller;
    protected $controller_namespace = 'App\\Http\\Controllers\\';
    protected $table_controller_namespace = 'App\\Http\\Controllers\\';

    /**
     * Requests
     * 1. Edit Request Name
     * 2. Store Request Name
     * 3. Create Request Name
     * 4. Update Request Name
     * 5. Delete Request Name
     * 6. Manage Request Name
     * 7. Edit Request Namespace
     * 8. Store Request Namespace
     * 9. Manage Request Namespace
     * 10. Create Request Namespace
     * 11. Update Request Namespace
     * 12. Delete Request Namespace
     * 13. Request Namespace.
     */
    protected $edit_request;
    protected $store_request;
    protected $create_request;
    protected $update_request;
    protected $delete_request;
    protected $manage_request;
    protected $edit_request_namespace;
    protected $store_request_namespace;
    protected $manage_request_namespace;
    protected $create_request_namespace;
    protected $update_request_namespace;
    protected $delete_request_namespace;
    protected $request_namespace = 'App\\Http\\Requests\\';

    /**
     * Permissions
     * 1. Edit Permission
     * 2. Store Permission
     * 3. Manage Permission
     * 4. Create Permission
     * 5. Update Permission
     * 6. Delete Permission.
     */
    protected $edit_permission;
    protected $store_permission;
    protected $manage_permission;
    protected $create_permission;
    protected $update_permission;
    protected $delete_permission;

    /**
     * Routes
     * 1. Edit Route
     * 2. Store Route
     * 3. Manage Route
     * 4. Create Route
     * 5. Update Route
     * 6. Delete Route.
     */
    protected $edit_route;
    protected $store_route;
    protected $index_route;
    protected $create_route;
    protected $update_route;
    protected $delete_route;

    /**
     * Repository
     * 1. Repository Name
     * 2. Repository Namespace.
     */
    protected $repository;
    protected $repo_namespace = 'App\\Repositories\\';

    /**
     * Table Name.
     */
    protected $table;

    /**
     * Events.
     *
     * @var array
     */
    protected $events = [];

    /**
     * Route Path.
     */
    protected $route_path = 'routes\\Generator\\';

    /**
     * View Path.
     */
    protected $view_path = 'resources\\views\\';

    protected $migration_path = 'database\\migrations\\';

    /**
     * Event Namespace.
     */
    protected $event_namespace = 'Backend\\';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->files = new Filesystem();
    }

    /**
     * Initialization.
     *
     * @param array $input
     */
    public function initialize($input)
    {
        // Original Name entered
        $this->originalName = Str::studly($input['name']);

        //Module
        $this->module = $this->originalName;

        //Directory
        $this->directory = Str::studly(ucwords($input['directory_name']));

        //Model
        $this->model = Str::studly(Str::singular($input['model_name']));

        //Table
        $this->table = strtolower($input['table_name']);

        //Controller
        $this->controller = Str::plural($this->model).'Controller';

        //Table Controller
        $this->table_controller = Str::plural($this->model).'TableController';

        //Attributes
        $this->attribute = $this->model.'Attribute';
        $this->attribute_namespace = $this->model_namespace;

        //Relationship
        $this->relationship = $this->model.'Relationship';
        $this->relationship_namespace = $this->model_namespace;

        //Repository
        $this->repository = $this->model.'Repository';

        //Requests
        $this->edit_request = 'Edit'.$this->model.'Request';
        $this->store_request = 'Store'.$this->model.'Request';
        $this->create_request = 'Create'.$this->model.'Request';
        $this->update_request = 'Update'.$this->model.'Request';
        $this->delete_request = 'Delete'.$this->model.'Request';
        $this->manage_request = 'Manage'.$this->model.'Request';

        //CRUD Options
        $this->upload = !empty($input['model_upload']) ? true : false;
        $this->edit = !empty($input['model_edit']) ? true : false;
        $this->create = !empty($input['model_create']) ? true : false;
        $this->delete = !empty($input['model_delete']) ? true : false;

        $this->columns = !empty($input['columns']) ? $input['columns'] : NULL ;

        $model_singular = strtolower(Str::singular($this->model));
        
        //Permissions
        $this->edit_permission = 'edit-'.$model_singular;
        $this->store_permission = 'store-'.$model_singular;
        $this->manage_permission = 'manage-'.$model_singular;
        $this->create_permission = 'create-'.$model_singular;
        $this->update_permission = 'update-'.$model_singular;
        $this->delete_permission = 'delete-'.$model_singular;

        $model_plural = strtolower(Str::plural($this->model));
        
        //Routes
        $this->index_route = 'admin.'.$model_plural.'.index';
        $this->create_route = 'admin.'.$model_plural.'.create';
        $this->store_route = 'admin.'.$model_plural.'.store';
        $this->edit_route = 'admin.'.$model_plural.'.edit';
        $this->update_route = 'admin.'.$model_plural.'.update';
        $this->delete_route = 'admin.'.$model_plural.'.destroy';

        //Events
        $this->events = array_filter($input['event']);

        //Generate Namespaces
        $this->createNamespacesAndValues();

        $this->table = "tests";
        $this->setColumns();
        $this->createMigration();
    }

    /**
     * @return void
     */
    public function createNamespacesAndValues()
    {
        //Model Namespace
        $this->model_namespace .= $this->getFullNamespace($this->model);

        //Controller Namespace
        $this->controller_namespace .= config('generator.controller_namespace').'\\'.$this->getFullNamespace($this->controller);

        //Table Controller Namespace
        $this->table_controller_namespace .= config('generator.controller_namespace').'\\'.$this->getFullNamespace($this->table_controller);

        //Attribute Namespace
        $this->attribute_namespace .= $this->getFullNamespace($this->attribute, $this->trait_directory);

        //Relationship Namespace
        $this->relationship_namespace .= $this->getFullNamespace($this->relationship, $this->trait_directory);

        //View Path
        $this->view_path .= config('generator.views_folder').'\\'.$this->getFullNamespace('');

        //Requests
        $this->request_namespace .= config('generator.request_namespace'). '\\';
        $this->edit_request_namespace = $this->request_namespace.$this->getFullNamespace($this->edit_request);
        $this->store_request_namespace = $this->request_namespace.$this->getFullNamespace($this->store_request);
        $this->manage_request_namespace = $this->request_namespace.$this->getFullNamespace($this->manage_request);
        $this->create_request_namespace = $this->request_namespace.$this->getFullNamespace($this->create_request);
        $this->update_request_namespace = $this->request_namespace.$this->getFullNamespace($this->update_request);
        $this->delete_request_namespace = $this->request_namespace.$this->getFullNamespace($this->delete_request);

        //Responses
        $this->responses_namespace .= config('generator.response_namespace'). '\\';

        $this->create_response_namespace = $this->responses_namespace.$this->getFullNamespace($this->create_response);
        $this->edit_response_namespace = $this->responses_namespace.$this->getFullNamespace($this->edit_response);

        //Repository Namespace
        $this->repo_namespace .= config('generator.request_namespace'). '\\' .$this->getFullNamespace($this->repository);

        //Events Namespace
        $this->event_namespace .= $this->getFullNamespace('');
    }

    /**
     * @return string
     */
    public function getModelNamespace()
    {
        return $this->model_namespace;
    }

    /**
     * @return string
     */
    public function getRequestNamespace()
    {
        return $this->request_namespace;
    }

    /**
     * @return string
     */
    public function getControllerNamespace()
    {
        return $this->controller_namespace;
    }

    /**
     * @return string
     */
    public function getRepoNamespace()
    {
        return $this->repo_namespace;
    }

    /**
     * @return string
     */
    public function getResponsesNamespace()
    {
        return $this->responses_namespace;
    }

    /**
     * @return string
     */
    public function getRoutePath()
    {
        return $this->route_path;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->view_path;
    }

    /**
     * Return the permissions used in the module.
     *
     * @return array
     */
    public function getPermissions()
    {
        $permissions = [
            $this->manage_permission
        ];

        if ($this->create) {
            $permissions[] = $this->create_permission;
            $permissions[] = $this->store_permission;
        }
        if ($this->edit) {
            $permissions[] = $this->edit_permission;
            $permissions[] = $this->update_permission;
        }
        if ($this->delete) {
            $permissions[] = $this->delete_permission;
        }

        return $permissions;
    }

    /**
     * @return string
     */
    public function getFullNamespace($name, $inside_directory = null)
    {
        if (empty($name)) {
            return $this->directory;
        } elseif ($inside_directory) {
            return $this->directory.'\\'.$inside_directory.'\\'.$name;
        } else {
            return $this->directory.'\\'.$name;
        }
    }

    /**
     * @return void
     */
    public function createModel()
    {
        $this->createDirectory($this->getBasePath($this->attribute_namespace, true));
        //Generate Attribute File
        $this->generateFile('Attribute', [
            'AttributeNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->attribute_namespace)),
            'AttributeClass'     => $this->attribute,
            'editPermission'     => $this->edit_permission,
            'editRoute'          => $this->edit_route,
            'deletePermission'   => $this->delete_permission,
            'deleteRoute'        => $this->delete_route,
        ], lcfirst($this->attribute_namespace));

        //Generate Relationship File
        $this->generateFile('Relationship', [
            'RelationshipNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->relationship_namespace)),
            'RelationshipClass'     => $this->relationship,
        ], lcfirst($this->relationship_namespace));

        //Generate Model File
        $this->generateFile('Model', [
            'DummyNamespace'    => ucfirst($this->removeFileNameFromEndOfNamespace($this->model_namespace)),
            'DummyAttribute'    => $this->attribute_namespace,
            'DummyRelationship' => $this->relationship_namespace,
            'AttributeName'     => $this->attribute,
            'RelationshipName'  => $this->relationship,
            'DummyModel'        => $this->model,
            'table_name'        => $this->table,
        ], lcfirst($this->model_namespace));
    }

    /**
     * @return void
     */
    public function createDirectory($path)
    {
        $this->files->makeDirectory($path, 0777, true, true);
    }

    /**
     * @return void
     */
    public function createRequests()
    {
        $this->request_namespace .= $this->getFullNamespace('');
        $this->createDirectory($this->getBasePath($this->request_namespace));
        
        //Generate Manage Request File
        $this->generateFile('Request', [
                'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->manage_request_namespace)),
                'DummyClass'     => $this->manage_request,
                'permission'     => $this->manage_permission,
            ], lcfirst($this->manage_request_namespace));

        if ($this->create) {
            //Generate Create Request File
            $this->generateFile('Request', [
                    'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->create_request_namespace)),
                    'DummyClass'     => $this->create_request,
                    'permission'     => $this->create_permission,
                ], lcfirst($this->create_request_namespace));

            //Generate Store Request File
            $this->generateFile('Request', [
                    'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->store_request_namespace)),
                    'DummyClass'     => $this->store_request,
                    'permission'     => $this->store_permission,
                ], lcfirst($this->store_request_namespace));
        }

        if ($this->edit) {
            //Generate Edit Request File
            $this->generateFile('Request', [
                    'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->edit_request_namespace)),
                    'DummyClass'     => $this->edit_request,
                    'permission'     => $this->edit_permission,
                ], lcfirst($this->edit_request_namespace));

            //Generate Update Request File
            $this->generateFile('Request', [
                    'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->update_request_namespace)),
                    'DummyClass'     => $this->update_request,
                    'permission'     => $this->update_permission,
                ], lcfirst($this->update_request_namespace));
        }

        if ($this->delete) {
            //Generate Delete Request File
            $this->generateFile('Request', [
                    'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->delete_request_namespace)),
                    'DummyClass'     => $this->delete_request,
                    'permission'     => $this->delete_permission,
                ], lcfirst($this->delete_request_namespace));
        }
    }

    /**
     * @return void
     */
    public function createRepository()
    {
        $this->createDirectory($this->getBasePath($this->repo_namespace, true));
        //Getting stub file content
        $file_contents = $this->files->get($this->getStubPath().'Repository.stub');
        //If Model Create is checked
        if (!$this->create) {
            $file_contents = $this->delete_all_between('@startCreate', '@endCreate', $file_contents);
        } else {//If it isn't
            $file_contents = $this->delete_all_between('@startCreate', '@startCreate', $file_contents);
            $file_contents = $this->delete_all_between('@endCreate', '@endCreate', $file_contents);
        }
        //If Model Edit is Checked
        if (!$this->edit) {
            $file_contents = $this->delete_all_between('@startEdit', '@endEdit', $file_contents);
        } else {//If it isn't
            $file_contents = $this->delete_all_between('@startEdit', '@startEdit', $file_contents);
            $file_contents = $this->delete_all_between('@endEdit', '@endEdit', $file_contents);
        }
        //If Model Delete is Checked
        if (!$this->delete) {
            $file_contents = $this->delete_all_between('@startDelete', '@endDelete', $file_contents);
        } else {//If it isn't
            $file_contents = $this->delete_all_between('@startDelete', '@startDelete', $file_contents);
            $file_contents = $this->delete_all_between('@endDelete', '@endDelete', $file_contents);
        }
        //Replacements to be done in repository stub file
        $replacements = [
                'DummyNamespace'                => ucfirst($this->removeFileNameFromEndOfNamespace($this->repo_namespace)),
                'DummyModelNamespace'           => $this->model_namespace,
                'DummyRepoName'                 => $this->repository,
                'dummy_model_name'              => $this->model,
                'dummy_small_model_name'        => strtolower($this->model),
                'model_small_plural'            => strtolower(Str::plural($this->model)),
                'dummy_small_plural_model_name' => strtolower(Str::plural($this->model)),
                'all_repositories_stuff' => $this->repositories,
        ];
        //Generating the repo file
        $this->generateFile(false, $replacements, lcfirst($this->repo_namespace), $file_contents);
    }

    /**
     * @return void
     */
    public function createResponses()
    {
        $this->responses_namespace .= $this->getFullNamespace('');
        $this->createDirectory($this->getBasePath($this->responses_namespace));

        if ($this->create) {
            //Generate CreateResponse File
            $this->generateFile('CreateResponse', [
                'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->create_response_namespace)),
                'dummy_small_plural_model' => strtolower(Str::plural($this->model)),
            ], lcfirst($this->create_response_namespace));
        }

        if ($this->edit) {
            //Generate EditResponse File
            $this->generateFile('EditResponse', [
                'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->edit_response_namespace)),
                'DummyModelNamespace'         => $this->model_namespace,
                'dummy_small_plural_model' => strtolower(Str::plural($this->model)),
            ], lcfirst($this->edit_response_namespace));
        }

        // if ($this->edit) {
        //     //Generate Create Request File
        //     $this->generateFile('Request', [
        //             'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->create_request_namespace)),
        //             'DummyClass'     => $this->create_request,
        //             'permission'     => $this->create_permission,
        //         ], lcfirst($this->create_request_namespace));

        //     //Generate Store Request File
        //     $this->generateFile('Request', [
        //             'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->store_request_namespace)),
        //             'DummyClass'     => $this->store_request,
        //             'permission'     => $this->store_permission,
        //         ], lcfirst($this->store_request_namespace));
        // }
    }

    /**
     * @return void
     */
    public function createController()
    {
        $this->createDirectory($this->getBasePath($this->controller_namespace, true));
        //Getting stub file content
        $file_contents = $this->files->get($this->getStubPath().'Controller.stub');
        //Replacements to be done in controller stub
        $replacements = [
            'DummyModelNamespace'         => $this->model_namespace,
            'DummyCreateResponseNamespace' => $this->create_response_namespace,
            'DummyEditResponseNamespace'   => $this->edit_response_namespace,
            'DummyModel'                  => $this->model,
            'DummyArgumentName'           => strtolower($this->model),
            'DummyManageRequestNamespace' => $this->manage_request_namespace,
            'DummyManageRequest'          => $this->manage_request,
            'DummyController'             => $this->controller,
            'DummyNamespace'              => ucfirst($this->removeFileNameFromEndOfNamespace($this->controller_namespace)),
            'DummyRepositoryNamespace'    => $this->repo_namespace,
            'dummy_repository'            => $this->repository,
            'dummy_small_plural_model'    => strtolower(Str::plural($this->model)),
        ];
        $namespaces = '';
        if (!$this->create) {
            $file_contents = $this->delete_all_between('@startCreate', '@endCreate', $file_contents);
        } else {
            $file_contents = $this->delete_all_between('@startCreate', '@startCreate', $file_contents);
            $file_contents = $this->delete_all_between('@endCreate', '@endCreate', $file_contents);

            //replacements
            $namespaces .= 'use '.$this->create_request_namespace.";\n";
            $namespaces .= 'use '.$this->store_request_namespace.";\n";
            $replacements['DummyCreateRequest'] = $this->create_request;
            $replacements['DummyStoreRequest'] = $this->store_request;
        }

        if (!$this->edit) {
            $file_contents = $this->delete_all_between('@startEdit', '@endEdit', $file_contents);
        } else {
            $file_contents = $this->delete_all_between('@startEdit', '@startEdit', $file_contents);
            $file_contents = $this->delete_all_between('@endEdit', '@endEdit', $file_contents);
            //replacements
            $namespaces .= 'use '.$this->edit_request_namespace.";\n";
            $namespaces .= 'use '.$this->update_request_namespace.";\n";
            $replacements['DummyEditRequest'] = $this->edit_request;
            $replacements['DummyUpdateRequest'] = $this->update_request;
        }

        if (!$this->delete) {
            $file_contents = $this->delete_all_between('@startDelete', '@endDelete', $file_contents);
        } else {
            $file_contents = $this->delete_all_between('@startDelete', '@startDelete', $file_contents);
            $file_contents = $this->delete_all_between('@endDelete', '@endDelete', $file_contents);
            //replacements
            $namespaces .= 'use '.$this->delete_request_namespace.";\n";
            $replacements['DummyDeleteRequest'] = $this->delete_request;
        }
        //Putting Namespaces in Controller
        $file_contents = str_replace('@Namespaces', $namespaces, $file_contents);

        $this->generateFile(false, $replacements, lcfirst($this->controller_namespace), $file_contents);
    }

    /**
     * @return void
     */
    public function createTableController()
    {
        $this->createDirectory($this->getBasePath($this->table_controller_namespace, true));
        //replacements to be done in table controller stub
        $replacements = [
            'DummyNamespace'              => ucfirst($this->removeFileNameFromEndOfNamespace($this->table_controller_namespace)),
            'DummyRepositoryNamespace'    => $this->repo_namespace,
            'DummyManageRequestNamespace' => $this->manage_request_namespace,
            'DummyTableController'        => $this->table_controller,
            'dummy_repository'            => $this->repository,
            'dummy_small_repo_name'       => strtolower($this->model),
            'dummy_manage_request_name'   => $this->manage_request,
        ];
        //generating the file
        $this->generateFile('TableController', $replacements, lcfirst($this->table_controller_namespace));
    }

    /**
     * @return void
     */
    public function createRouteFiles()
    {
        $this->createDirectory($this->getBasePath($this->route_path));

        if ($this->create && $this->edit && $this->delete) {//Then get the resourceRoute stub
            //Getting stub file content
            $file_contents = $this->files->get($this->getStubPath().'resourceRoute.stub');
            $file_contents = $this->delete_all_between('@startNamespace', '@startNamespace', $file_contents);
            $file_contents = $this->delete_all_between('@endNamespace', '@endNamespace', $file_contents);
            $file_contents = $this->delete_all_between('@startWithoutNamespace', '@endWithoutNamespace', $file_contents);
        } else {//Get the basic route stub
            //Getting stub file content
            $file_contents = $this->files->get($this->getStubPath().'route.stub');
            $file_contents = $this->delete_all_between('@startNamespace', '@startNamespace', $file_contents);
            $file_contents = $this->delete_all_between('@endNamespace', '@endNamespace', $file_contents);
            $file_contents = $this->delete_all_between('@startWithoutNamespace', '@endWithoutNamespace', $file_contents);
            //If create is checked
            if ($this->create) {
                $file_contents = $this->delete_all_between('@startCreate', '@startCreate', $file_contents);
                $file_contents = $this->delete_all_between('@endCreate', '@endCreate', $file_contents);
            } else {//If it isn't
                $file_contents = $this->delete_all_between('@startCreate', '@endCreate', $file_contents);
            }
            //If Edit is checked
            if ($this->edit) {
                $file_contents = $this->delete_all_between('@startEdit', '@startEdit', $file_contents);
                $file_contents = $this->delete_all_between('@endEdit', '@endEdit', $file_contents);
            } else {//if it isn't
                $file_contents = $this->delete_all_between('@startEdit', '@endEdit', $file_contents);
            }
            //If delete is checked
            if ($this->delete) {
                $file_contents = $this->delete_all_between('@startDelete', '@startDelete', $file_contents);
                $file_contents = $this->delete_all_between('@endDelete', '@endDelete', $file_contents);
            } else {//If it isn't
                $file_contents = $this->delete_all_between('@startDelete', '@endDelete', $file_contents);
            }
        }
        //Generate the Route file
        $this->generateFile(false, [
            'route_namespace'      => config('generator.controller_namespace'),
            'DummyModuleName'      => $this->module,
            'DummyModel'           => $this->directory,
            'dummy_name'           => strtolower(Str::plural($this->model)),
            'DummyController'      => $this->controller,
            'DummyTableController' => $this->table_controller,
            'dummy_argument_name'  => strtolower($this->model),
        ], $this->route_path.$this->model, $file_contents);

        //Routes web.php file
        $web_file = base_path('routes/web.php');
        //file_contents of Backend.php
        $file_contents = file_get_contents($web_file);
        //If this is already not there, then only append
        if (!strpos($file_contents, "includeRouteFiles(__DIR__.'/Generator/');")) {
            $content = "\n/*\n* Routes From Module Generator\n*/\nincludeRouteFiles(__DIR__.'/Generator/');";
            //Appending into web.php file
            file_put_contents($web_file, $content, FILE_APPEND);
        }
    }

    /**
     * This would enter the necessary language file contents to respective language files.
     *
     * @param [array] $input
     */
    public function insertToLanguageFiles()
    {
        //Model singular version
        $model_singular = Str::singular($this->originalName);
        //Model Plural version
        $model_plural = Str::plural($this->originalName);
        //Model Plural key
        $model_plural_key = strtolower(Str::plural($this->model));
        //Path to that language files
        $path = resource_path('lang'.DIRECTORY_SEPARATOR.'en');
        //config folder path
        $config_path = config_path('module.php');
        //Creating directory if it isn't
        $this->createDirectory($path);
        //Labels file
        $labels = [
            'create'     => "Create $model_singular",
            'edit'       => "Edit $model_singular",
            'management' => "$model_singular Management",
            'title'      => "$model_plural",

            'table' => $this->labels
        ];
        //Pushing values to labels
        add_key_value_in_file($path.'/labels.php', [$model_plural_key => $labels], 'backend');
        //Menus file
        $menus = [
            'all'        => "All $model_plural",
            'create'     => "Create $model_singular",
            'edit'       => "Edit $model_singular",
            'management' => "$model_singular Management",
            'main'       => "$model_plural",
        ];
        //Pushing to menus file
        add_key_value_in_file($path.'/menus.php', [$model_plural_key => $menus], 'backend');
        //Exceptions file
        $exceptions = [
            'already_exists' => "That $model_singular already exists. Please choose a different name.",
            'create_error'   => "There was a problem creating this $model_singular. Please try again.",
            'delete_error'   => "There was a problem deleting this $model_singular. Please try again.",
            'not_found'      => "That $model_singular does not exist.",
            'update_error'   => "There was a problem updating this $model_singular. Please try again.",
        ];
        //Alerts File
        $alerts = [
            'created' => "The $model_singular was successfully created.",
            'deleted' => "The $model_singular was successfully deleted.",
            'updated' => "The $model_singular was successfully updated.",
        ];
        //Pushing to menus file
        add_key_value_in_file($path.'/alerts.php', [$model_plural_key => $alerts], 'backend');
        //Pushing to exceptions file
        add_key_value_in_file($path.'/exceptions.php', [$model_plural_key => $exceptions], 'backend');
        //config file "module.php"
        $config = [
            $model_plural_key => [
                'table' => $this->table,
            ],
        ];
        //Pushing to config file
        add_key_value_in_file($config_path, $config);
    }

    /**
     * Creating View Files.
     *
     * @param array $input
     */
    public function createViewFiles()
    {
        //Getiing model
        $model = $this->model;
        //lowercase version of model
        $model_lower = strtolower($model);
        //lowercase and plural version of model
        $model_lower_plural = Str::plural($model_lower);
        //View folder name
        $view_folder_name = $model_lower_plural;
        //View path
        $path = escapeSlashes(strtolower(Str::plural($this->view_path)));
        //Header buttons folder
        $header_button_path = $path.DIRECTORY_SEPARATOR.'partials';
        //This would create both the directory name as well as partials inside of that directory
        $this->createDirectory(base_path($header_button_path));
        //Header button full path
        $header_button_file_path = $header_button_path.DIRECTORY_SEPARATOR."$model_lower_plural-header-buttons.blade";
        //Getting stub file content
        $header_button_contents = $this->files->get($this->getStubPath().'header-buttons.stub');
        if (!$this->create) {
            $header_button_contents = $this->delete_all_between('@create', '@endCreate', $header_button_contents);
        } else {
            $header_button_contents = $this->delete_all_between('@create', '@create', $header_button_contents);
            $header_button_contents = $this->delete_all_between('@endCreate', '@endCreate', $header_button_contents);
        }
        //Generate Header buttons file
        $this->generateFile(false, ['dummy_small_plural_model' => $model_lower_plural, 'dummy_small_model' => $model_lower], $header_button_file_path, $header_button_contents);
        //Index blade
        $index_path = $path.DIRECTORY_SEPARATOR.'index.blade';
        //Generate the Index blade file
        $this->generateFile('index_view', [
            'dummy_small_plural_model' => $model_lower_plural,
            'index_thead' => $this->indexes['index_thead'],
            'index_empty_th' => $this->indexes['index_empty_th'],
            'index_data' => $this->indexes['index_data'],
        ], $index_path);
        //Create Blade
        if ($this->create) {
            //Create Blade
            $create_path = $path.DIRECTORY_SEPARATOR.'create.blade';
            //Generate Create Blade
            $this->generateFile('create_view', ['dummy_small_plural_model' => $model_lower_plural, 'dummy_small_model' => $model_lower, 'files_upload_permission'=> $this->upload], $create_path);
        }
        //Edit Blade
        if ($this->edit) {
            //Edit Blade
            $edit_path = $path.DIRECTORY_SEPARATOR.'edit.blade';
            //Generate Edit Blade
            $this->generateFile('edit_view', ['dummy_small_plural_model' => $model_lower_plural, 'dummy_small_model' => $model_lower, 'files_upload_permission' => $this->upload], $edit_path);
        }
        //Form Blade
        if ($this->create || $this->edit) {
            //Form Blade
            $form_path = $path.DIRECTORY_SEPARATOR.'form.blade';
            //Generate Form Blade
            $this->generateFile('form_view', [
                'dummy_small_plural_model' => $model_lower_plural,
                'dummy_small_model' => $model_lower,
                'all_form_stuff' => $this->form_elements
            ], $form_path);
        }
        //BreadCrumbs Folder Path
        $breadcrumbs_path = escapeSlashes('app\\Http\\Breadcrumbs\\Backend');
        //Breadcrumb File path
        $breadcrumb_file_path = $breadcrumbs_path.DIRECTORY_SEPARATOR.$this->model;
        //Generate BreadCrumb File
        $this->generateFile('Breadcrumbs', ['dummy_small_plural_model' => $model_lower_plural], $breadcrumb_file_path);
        //Backend File of Breadcrumb
        $breadcrumb_backend_file = $breadcrumbs_path.DIRECTORY_SEPARATOR.'Backend.php';
        //file_contents of Backend.php
        $file_contents = file_get_contents(base_path($breadcrumb_backend_file));
        //If this is already not there, then only append
        if (!strpos($file_contents, "require __DIR__.'/$this->model.php';")) {
            //Appending into BreadCrumb backend file
            file_put_contents(base_path($breadcrumb_backend_file), "\nrequire __DIR__.'/$this->model.php';", FILE_APPEND);
        }
    }

    /**
     * Creating Table File.
     *
     * @param array $input
     */
    public function createMigration()
    {
        $table = $this->table;

        if (Schema::hasTable($table)) {
            return 'Table Already Exists!';
        } else {
            //Calling Artisan command to create table
            // Artisan::call('make:migration', [
            //     'name'     => 'create_'.$table.'_table',
            //     '--create' => $table,
            // ]);

            // return Artisan::output();
            $path = escapeSlashes(strtolower(($this->migration_path)));
            $dummy_small_plural_model = Str::plural(strtolower($this->model));
            $migration_path = $path.DIRECTORY_SEPARATOR.Carbon::now()->format('Y_m_d_His').'_create_'.$dummy_small_plural_model.'_table';
            $this->generateFile('migration', 
                [
                    'dummy_small_plural_model' => $dummy_small_plural_model,
                    'dummy_name' => $this->model,
                    'all_migrations_stuff' => $this->migrations,
                ]
                , $migration_path
            );
        }
    }

    /**
     * Creating Event Files.
     *
     * @param array $input
     */
    public function createEvents()
    {
        if (!empty($this->events)) {
            $base_path = $this->event_namespace;

            foreach ($this->events as $event) {
                $path = escapeSlashes($base_path.DIRECTORY_SEPARATOR.$event);
                $model = str_replace(DIRECTORY_SEPARATOR, '\\', $path);
                
                Artisan::call('make:event', [
                    'name' => $model,
                ]);
                
                Artisan::call('make:listener', [
                    'name'    => $model.'Listener',
                    '--event' => $model,
                ]);
            }
        }
    }

    /**
     * Generating the file by
     * replacing placeholders in stub file.
     *
     * @param $stub_name Name of the Stub File
     * @param $replacements [array] Array of the replacement to be done in stub file
     * @param $file [string] full path of the file
     * @param $contents [string][optional] file contents
     */
    public function generateFile($stub_name, $replacements, $file, $contents = null)
    {
        if ($stub_name) {
            //Getting the Stub Files Content
            $file_contents = $this->files->get($this->getStubPath().$stub_name.'.stub');
        } else {
            //Getting the Stub Files Content
            $file_contents = $contents;
        }
        //Replacing the stub
        $file_contents = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $file_contents
        );
        $this->files->put(base_path(escapeSlashes($file)).'.php', $file_contents);
    }

    /**
     * getting the stub folder path.
     *
     * @return string
     */
    public function getStubPath()
    {
        $path = resource_path('views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'generator'.DIRECTORY_SEPARATOR.'Stubs'.DIRECTORY_SEPARATOR);
        $package_stubs_path = base_path('vendor'.DIRECTORY_SEPARATOR.'glumbo'.DIRECTORY_SEPARATOR.'generator'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'Stubs'.DIRECTORY_SEPARATOR);
        if ($this->files->exists($path)) {
            return $path;
        } else {
            return $package_stubs_path;
        }
    }

    /**
     * getBasePath
     *
     * @param string $namespace
     * @param bool $status
     * @return string
     */
    public function getBasePath($namespace, $status = false)
    {
        if ($status) {
            return base_path(escapeSlashes($this->removeFileNameFromEndOfNamespace($namespace, $status)));
        }

        return base_path(lcfirst(escapeSlashes($namespace)));
    }

    /**
     * Removes the filename from the passed the namespace
     *
     * @param string $namespace
     * @return string
     */
    public function removeFileNameFromEndOfNamespace($namespace)
    {
        $namespace = explode('\\', $namespace);

        unset($namespace[count($namespace) - 1]);

        return lcfirst(implode('\\', $namespace));
    }

    /**
     * Modify strings by removing content between $beginning and $end.
     *
     * @param string $beginning
     * @param string $end
     * @param string $string
     *
     * @return string
     */
    public function delete_all_between($beginning, $end, $string)
    {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return str_replace($textToDelete, '', $string);
    }

    public function setColumns(){
        $this->setMigrations();
        $this->setRepositories();
        $this->setLabels();
        $this->setIndex();
        $this->setForm();
    }

    public function setMigrations(){
        $migrations = "";
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                if($column["type"] == 0){
                    $migrations .= '$table->integer(\''.$column['name'].'\')';
                }elseif ($column["type"] == 1 || $column["type"] == 5) {
                    $migrations .= '$table->string(\''.$column['name'].'\')';
                }elseif ($column["type"] == 2) {
                    $migrations .= '$table->text(\''.$column['name'].'\')';
                }elseif ($column["type"] == 3) {
                    $migrations .= '$table->decimal(\''.$column['name'].'\', 8, 2)';
                }elseif ($column["type"] == 4) {
                    $migrations .= '$table->timestamp(\''.$column['name'].'\')';
                }

                if(isset($column["nullable"])){
                    $migrations = $migrations."->nullable()";
                }
                if(isset($column["default"])){
                    $migrations = $migrations."->defalut(".$column["default"].")";
                }
                $migrations .= ";\n\t\t\t";
            }
        }
        $this->migrations = $migrations;
    }
    public function setRepositories(){
        $repositories = "";
        $model_small_plural = Str::plural(strtolower($this->model));
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                $repositories .= 'config(\'module.' .$model_small_plural. '.table\').\'.'.$column["name"].'\'';
                $repositories .= ",\n\t\t\t\t";
            }
        }
        $this->repositories = $repositories;
    }
    public function setLabels(){
        $table = [
            'id'        => 'Id',
        ];
        $model_small_plural = Str::plural(strtolower($this->model));
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                $table[$column["name"]] = ucfirst($column["name"]);
            }
        }

        $table['createdat'] ='Created At';
        $this->labels = $table;
    }
    public function setIndex(){
        $indexes = array();
        $indexes['index_thead'] = "";
        $indexes['index_empty_th'] = "";
        $indexes['index_data'] = "";

        $model_small_plural = Str::plural(strtolower($this->model));
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                $indexes['index_thead'] .= '<th>{{ trans(\'labels.backend.'.$model_small_plural.'.table.'.$column["name"].'\') }}</th>'."\n\t\t\t\t\t\t\t";
                $indexes['index_empty_th'] .= '<th></th>'."\n\t\t\t\t\t\t\t";
                $indexes['index_data'] .= '{data: \''.$column["name"].'\', name: \'{{config(\'module.'.$model_small_plural.'.table\')}}.'.$column["name"].'\'},'."\n\t\t\t\t\t";
            }
        }
        $this->indexes = $indexes;
    }

    public function setForm(){
        $form_elements = "";
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                $form_elements .= $this->getFormElement($column["type"], $column["name"], isset($column["nullable"]) ? $column["nullable"] : false)."\n\t\t";
            }
        }
        $this->form_elements = $form_elements;
    }

    public function getFormElement($type, $name,$nullable = false){
        switch ($type) {
            case '0':
                return $this->getFormElementNumber($name,$nullable);
                break;
            case '1':
                return $this->getFormElementString($name,$nullable);
                break;
            case '2':
                return $this->getFormElementText($name,$nullable);
                break;
            case '3':
                return $this->getFormElementDecimal($name,$nullable);
                break;
            case '4':
                return $this->getFormElementTimestamp($name,$nullable);
                break;
            case '5':
                return $this->getFormElementFile($name,$nullable);
                break;
            
            default:
                return $this->getFormElementString($name,$nullable);
                break;
        }
    }
    public function getFormElementNumber($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
        '<div class="form-group {{ ($errors->has(\''.$name.'\') ? \' has-error\' : \'\') }}">
            {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label '.($nullable ? '' : 'required') .'\']) }}
            <div class="col-lg-10">
                {{ Form::number(\''.$name.'\', null, [\'class\' => \'form-control box-size\', \'placeholder\' => _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), '.($nullable ? '' : '\'required\' => \'required\'').']) }}
                @if ($errors->has(\''.$name.'\'))
                    <span class="help-block">
                        <strong>{{ $errors->first(\''.$name.'\') }}</strong>
                    </span>
                @endif
            </div><!--col-lg-10-->
        </div><!--form-group-->';
    }

    public function getFormElementString($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
        '<div class="form-group {{ $errors->has(\''.$name.'\') ? \' has-error\' : \'\' }}">
            {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label '.($nullable ? '' : 'required') .'\']) }}
            <div class="col-lg-10">
                {{ Form::text(\''.$name.'\', null, [\'class\' => \'form-control box-size\', \'placeholder\' => _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), '.($nullable ? '' : '\'required\' => \'required\'').']) }}
                @if ($errors->has(\''.$name.'\'))
                    <span class="help-block">
                        <strong>{{ $errors->first(\''.$name.'\') }}</strong>
                    </span>
                @endif
            </div><!--col-lg-10-->
        </div><!--form-group-->';
    }
    public function getFormElementText($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
            '<div class="form-group {{ $errors->has(\''.$name.'\') ? \' has-error\' : \'\' }}">
                {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label '.($nullable ? '' : 'required') .'\']) }}
                <div class="col-lg-10 mce-box">
                    {{ Form::textarea(\''.$name.'\', null,[\'class\' => \'form-control\', \'placeholder\' => _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), '.($nullable ? '' : '\'required\' => \'required\'').']) }}
                    @if ($errors->has(\''.$name.'\'))
                        <span class="help-block">
                            <strong>{{ $errors->first(\''.$name.'\') }}</strong>
                        </span>
                    @endif
                </div><!--col-lg-3-->
            </div><!--form control-->';
    }

    public function getFormElementDecimal($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
            '<div class="form-group {{ $errors->has(\''.$name.'\') ? \' has-error\' : \'\' }}">
                {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label '.($nullable ? '' : 'required') .'\']) }}
                <div class="col-lg-10">
                    {{ Form::number(\''.$name.'\', null, [\'class\' => \'form-control box-size\', \'placeholder\' => _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), \'step\' => \'0.01\' ,'.($nullable ? '' : '\'required\' => \'required\'').']) }}
                    @if ($errors->has(\''.$name.'\'))
                        <span class="help-block">
                            <strong>{{ $errors->first(\''.$name.'\') }}</strong>
                        </span>
                    @endif
                </div><!--col-lg-10-->
            </div><!--form-group-->';
    }

    public function getFormElementTimestamp($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
            '<div class="form-group {{ $errors->has(\''.$name.'\') ? \' has-error\' : \'\' }}">
                {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label '.($nullable ? '' : 'required') .'\']) }}
                <div class="col-lg-10">
                    {{ Form::text(\''.$name.'\', null, [\'class\' => \'form-control datetimepicker1 box-size\', \'id\' => \'datetimepicker1\', \'placeholder\' => _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), '.($nullable ? '' : '\'required\' => \'required\'').']) }}
                    @if ($errors->has(\''.$name.'\'))
                        <span class="help-block">
                            <strong>{{ $errors->first(\''.$name.'\') }}</strong>
                        </span>
                    @endif
                </div><!--col-lg-10-->
            </div><!--form-group-->';
    }
    public function getFormElementFile($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return 
            '<div class="form-group {{ $errors->has(\''.$name.'\') ? \' has-error\' : \'\' }}">
                {{ Form::label(\''.$name.'\', _tr(\'labels.backend.'.$model_small_plural.'.table.'.$name.'\'), [\'class\' => \'col-lg-2 control-label\']) }}
                @if(!empty($'.$model_small_plural.'->'.$name.'))
                    <div class="col-lg-1 file_show">
                        <img src="{{ Storage::disk(\'public\')->url($'.$model_small_plural.'->'.$name.') }}" height="80" width="80">
                        <i class="fal fa-times remove_file"></i>
                    </div>
                    <div class="col-lg-5">
                        <div class="custom-file-input">
                            <input type="file" name="'.$name.'" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" />
                            <label for="file-1"><i class="fal fa-upload"></i><span>Choose a file</span></label>
                            <input type="hidden" name="remove_file" id="remove_file" value="0" />
                        </div>
                    </div>
                @else
                    <div class="col-lg-5">
                        <div class="custom-file-input">
                                <input type="file" name="'.$name.'" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" '.($nullable ? '' : 'required') .'/>
                                <label for="file-1" class="'.($nullable ? '' : 'required').'"><i class="fal fa-upload"></i><span>Choose a file</span></label>
                        </div>
                    </div>
                @endif
            </div><!--form control-->';
    }
}
