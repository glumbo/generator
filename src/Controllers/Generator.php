<?php

namespace Glumbo\Generator\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
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
    protected $api_controller_namespace = 'App\\Http\\Controllers\\Api\\';
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
    protected $breadcrumbs_namespace = 'App\\Http\\Breadcrumbs\\Backend';

    protected $resource_namespace = 'App\\Http\\Resources';
    protected $resource;

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

        //Resource
        $this->resource = $this->model.'Resource';

        //Requests
        $this->edit_request = 'Edit'.$this->model.'Request';
        $this->store_request = 'Store'.$this->model.'Request';
        $this->create_request = 'Create'.$this->model.'Request';
        $this->update_request = 'Update'.$this->model.'Request';
        $this->delete_request = 'Delete'.$this->model.'Request';
        $this->manage_request = 'Manage'.$this->model.'Request';

        //CRUD Options
        $this->upload = !empty($input['model_upload']) ? 'true' : 'false';
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

        //Api Controller Namespace
        $this->api_controller_namespace .= config('generator.api_version').'\\'.$this->controller;

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

        //Resource Namespace
        $this->resource_namespace .= config('generator.resource_namespace'). '\\' .$this->resource;

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
                'all_model_paths' => $this->all_model_paths,
                'all_relations' => $this->all_relations,
                'compact_relations_array' => 'compact('.$this->compact_relations_array.')',
                'relation_small_plural' => $this->model_namespace,
            ], lcfirst($this->create_response_namespace));
        }

        if ($this->edit) {
            //Generate EditResponse File
            $this->generateFile('EditResponse', [
                'DummyNamespace' => ucfirst($this->removeFileNameFromEndOfNamespace($this->edit_response_namespace)),
                'DummyModelNamespace'         => $this->model_namespace,
                'dummy_small_plural_model' => strtolower(Str::plural($this->model)),
                'all_model_paths' => $this->all_model_paths,
                'all_relations' => $this->all_relations,
                'compact_relations_array' => 'compact('.$this->compact_relations_array.')',
                'relation_small_plural' => $this->model_namespace,
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

    public function createResources()
    {
        $this->createDirectory($this->getBasePath($this->resource_namespace, true));
        //Getting stub file content
        $file_contents = $this->files->get($this->getStubPath().'Resource.stub');
        //If Model Create is checked
        //Replacements to be done in repository stub file
        $replacements = [
            'DummyNamespace'                => ucfirst($this->removeFileNameFromEndOfNamespace($this->resource_namespace)),
            'DummyResourceName'             => $this->resource,
            'resource_array'                => $this->getResourceArray(),
        ];
        //Generating the repo file
        $c = $this->generateFile(false, $replacements, lcfirst($this->resource_namespace), $file_contents);
    }

    /**
     * @return void
     */
    public function createApiController()
    {
        $this->createDirectory($this->getBasePath($this->api_controller_namespace, true));
        //Getting stub file content
        $file_contents = $this->files->get($this->getStubPath().'ApiController.stub');
        //Replacements to be done in controller stub
        $replacements = [
            'DummyModelNamespace'         => $this->model_namespace,
            'DummyModel'                  => $this->model,
            'DummyArgumentName'           => strtolower($this->model),
            'DummyResourceNamespace'      => $this->resource_namespace,
            'DummyResource'               => $this->resource,
            'DummyController'             => $this->controller,
            'DummyNamespace'              => ucfirst($this->removeFileNameFromEndOfNamespace($this->api_controller_namespace)),
            'DummyRepositoryNamespace'    => $this->repo_namespace,
            'validateDummy'               => 'validate'.ucfirst($this->model),
            'dummy_repository'            => $this->repository,
            'dummy_small_plural_model'    => strtolower(Str::plural($this->model)),
            'dummy_small_model'           => strtolower($this->model),
            'all_validation_array'        => $this->getValidateArray(),
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

        $g = $this->generateFile(false, $replacements, lcfirst($this->api_controller_namespace), $file_contents);
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
                    'dummy_plural_model' => Str::plural($this->model),
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
    public function deleteAllFiles(){
        $this->deleteMigration();
        $this->deleteModel();
        $this->deleteRequests();
        $this->deleteResponses();
        $this->deleteRepository();
        $this->deleteControllers();
        $this->deleteResources();
        $this->deleteRouteFiles();
        $this->deleteBreadCrumb();
        $this->deleteViewFiles();
        $this->deleteEvents();
        $this->removeLanguageFiles();
    }

    public function deleteMigration(){
        $path = escapeSlashes(strtolower(($this->migration_path)));
        $dummy_small_plural_model = Str::plural(strtolower($this->model));
        $migration_path = '_create_'.$dummy_small_plural_model.'_table';

        $files = File::allFiles($path);
        $files = array_filter($files, function ($file) use ($migration_path) {
            return (strpos($file->getFilename(), $migration_path) > 0);
        });
        foreach ($files as $file){
            if(File::exists($file->getRealPath())){
                File::delete($file);
            }
        }
    }
    public function deleteModel(){
        $model_namespace = $this->removeFileNameFromEndOfNamespace($this->model_namespace);
        if(File::isDirectory($model_namespace)){
            File::deleteDirectory($model_namespace);
        }
    }
    public function deleteRequests(){
        $request_namespace = ucfirst($this->removeFileNameFromEndOfNamespace($this->manage_request_namespace));
        if(File::isDirectory($request_namespace)){
            File::deleteDirectory($request_namespace);
        }
    }
    public function deleteResponses(){
        $responses_namespace = ucfirst($this->removeFileNameFromEndOfNamespace($this->create_response_namespace));
        if(File::isDirectory($responses_namespace)){
            File::deleteDirectory($responses_namespace);
        }
    }
    public function deleteRepository(){
        $repo_namespace = ucfirst($this->removeFileNameFromEndOfNamespace($this->repo_namespace));
        if(File::isDirectory($repo_namespace)){
            File::deleteDirectory($repo_namespace);
        }
    }
    public function deleteControllers(){
        $controller_namespace = ucfirst($this->removeFileNameFromEndOfNamespace($this->controller_namespace));
        if(File::isDirectory($controller_namespace)){
            File::deleteDirectory($controller_namespace);
        }
        $api_controller_namespace = ucfirst($this->removeFileNameFromEndOfNamespace($this->api_controller_namespace));
        $files = File::allFiles($api_controller_namespace);
        $files = array_filter($files, function ($file) {
            return (strpos($file->getFilename(), $this->controller) !== false);
        });
        foreach ($files as $file){
            if(File::exists($file->getRealPath())){
                File::delete($file);
            }
        }
    }
    public function deleteResources(){
        $path = ucfirst($this->removeFileNameFromEndOfNamespace($this->resource_namespace));
        $files = File::allFiles($path);
        $files = array_filter($files, function ($file) {
            return (strpos($file->getFilename(), $this->resource) !== false);
        });
        foreach ($files as $file){
            if(File::exists($file->getRealPath())){
                File::delete($file);
            }
        }
    }
    public function deleteRouteFiles(){
        $path = ucfirst($this->removeFileNameFromEndOfNamespace($this->route_path));
        $files = File::allFiles($path);
        $files = array_filter($files, function ($file) {
            return (strpos($file->getFilename(), $this->model.'.php') !== false);
        });
        foreach ($files as $file){
            if(File::exists($file->getRealPath())){
                File::delete($file);
            }
        }
    }
    public function deleteBreadCrumb(){
        $breadcrumb_path = ucfirst($this->removeFileNameFromEndOfNamespace($this->breadcrumbs_namespace));
        $breadcrumb_backend_path = $breadcrumb_path.'\Backend\Backend.php';
        if(File::exists($breadcrumb_backend_path)){
            $fileContents = File::get($breadcrumb_backend_path);
            $fileContents = str_replace("\n", "\r\n", str_replace("\r\n", "\n", $fileContents));

            // Dosya içeriğini satır satır diziye dönüştür
            $lines = explode(PHP_EOL, $fileContents);

            // $variable değişkeninin değerini içeren satırları filtrele
            $filteredLines = array_filter($lines, function ($line) {
                return stripos($line, $this->model.'.php') === false;
            });
            // Filtrelenmiş satırları birleştir
            $newContent = implode(PHP_EOL, $filteredLines);


            // Yeni içeriği dosyaya yaz
            File::put($breadcrumb_backend_path, $newContent);
        }

        $files = File::allFiles($breadcrumb_path);
        $files = array_filter($files, function ($file) {
            return (strpos($file->getFilename(), $this->model.'.php') !== false);
        });
        foreach ($files as $file){
            if(File::exists($file->getRealPath())){
                File::delete($file);
            }
        }
    }
    public function deleteViewFiles(){
        $view_path = $path = escapeSlashes(strtolower(Str::plural($this->view_path)));
        if(File::isDirectory($view_path)){
            File::deleteDirectory($view_path);
        }
    }
    public function deleteEvents(){
        $event_namespace = escapeSlashes('App\\Events\\'.$this->event_namespace.DIRECTORY_SEPARATOR);
        $listener_namespace = escapeSlashes('App\\Listeners\\'.$this->event_namespace.DIRECTORY_SEPARATOR);
        if(File::isDirectory($event_namespace)){
            File::deleteDirectory($event_namespace);
        }
        if(File::isDirectory($listener_namespace)){
            File::deleteDirectory($listener_namespace);
        }
    }
    public function removeLanguageFiles(){
        //Path to that language files
        $path = resource_path('lang'.DIRECTORY_SEPARATOR.'en');
        //Model Plural key
        $model_plural_key = strtolower(Str::plural($this->model));

        //Pushing values to labels
        remove_key_value_in_file($path.'/labels.php', $model_plural_key, 'backend');
        remove_key_value_in_file($path.'/menus.php', $model_plural_key, 'backend');
        remove_key_value_in_file(config_path('module.php'), $model_plural_key);
        remove_key_value_in_file($path.'/exceptions.php', $model_plural_key, 'backend');
        remove_key_value_in_file($path.'/alerts.php', $model_plural_key, 'backend');
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
        return $this->files->put(base_path(escapeSlashes($file)).'.php', $file_contents);
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
        $this->setResponses();
    }

    public function setMigrations(){
        $migrations = "";
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"])){
                if($column["type"] == 0 || $column["type"] == 6){
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
    public function getValidateArray(){
        $validateArray = [];
        $ruleArray = [];
        $rulesString = "[\n";
        foreach ($this->columns as $key => $column) {
            if(empty($column['nullable'])){
                array_push($ruleArray, 'required');
            }
            if($column["type"] == 1){
                array_push($ruleArray, 'max:191');
            }elseif($column["type"] == 3){
                array_push($ruleArray, 'decimal:2');
            }elseif($column["type"] == 4){
                array_push($ruleArray, 'date');
            }elseif($column["type"] == 5){
                array_push($ruleArray, 'numeric');
            }
            if(count($ruleArray) > 0 && !empty($column['name'])){
                $ruleString = implode('|', $ruleArray);
                $rulesString .= "               '".$column['name']."' => '".$ruleString."',\n";
            }
            $ruleArray = [];
        }
        $rulesString .= "               ]";
        return $rulesString;
    }
    public function getResourceArray(){
        $resource_array = [];
        $resourceString = "[\n";
        foreach ($this->columns as $key => $column) {
            if(!empty($column['name'])){
                $resourceString .= "                '".$column['name']."' => \$this->".$column['name'].",\n";
            }
        }
        $resourceString .= "                ]";
        return $resourceString;
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
                $indexes['index_thead'] .= '<th>{{ _tr(\'labels.backend.'.$model_small_plural.'.table.'.$column["name"].'\') }}</th>'."\n\t\t\t\t\t\t\t";
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
                $form_elements .= $this->getFormElement($column["type"], $column["name"], isset($column["nullable"]) ? $column["nullable"] : false, isset($column["relation"]) ? $column["relation"] : false)."\n\t\t";
            }
        }
        $this->form_elements = $form_elements;
    }

    public function getFormElement($type, $name,$nullable = false, $relation){
        switch ($type) {
            case '0':
                return $this->getFormElementNumber($name,$nullable);
            case '1':
                return $this->getFormElementString($name,$nullable);
            case '2':
                return $this->getFormElementText($name,$nullable);
            case '3':
                return $this->getFormElementDecimal($name,$nullable);
            case '4':
                return $this->getFormElementTimestamp($name,$nullable);
            case '5':
                return $this->getFormElementImage($name,$nullable);
            case '6':
                return $this->getFormElementSelect($name,$nullable,$relation);
            case '7':
                return $this->getFormElementMultipleSelect($name,$nullable,$relation);
            
            default:
                return $this->getFormElementString($name,$nullable);
        }
    }
    public function getFormElementNumber($name,$nullable){
        return 
        '<x-backend.input-number :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').'></x-backend.input-number>';
    }

    public function getFormElementString($name,$nullable){
        return
            '<x-backend.input-text :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').'></x-backend.input-number>';
    }
    public function getFormElementText($name,$nullable){
        return
            '<x-backend.text-area-editor :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').'></x-backend.input-number>';
    }

    public function getFormElementDecimal($name,$nullable){
        return
            '<x-backend.input-number :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').' step="0.01"></x-backend.input-number>';;
    }

    public function getFormElementTimestamp($name,$nullable){
        return
            '<x-backend.date-time :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').'></x-backend.input-number>';;
    }
    public function getFormElementFile($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return
            '<x-backend.file :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').' :module="'.('$'.$model_small_plural).'"></x-backend.image>';
    }

    public function getFormElementImage($name,$nullable){
        $model_small_plural = Str::plural(strtolower($this->model));
        return
            '<x-backend.image :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').' :module="'.('$'.$model_small_plural).'"></x-backend.image>';
    }
    public function getFormElementSelect($name,$nullable,$relation){
        $relation_small_plural = Str::plural(strtolower($relation));
        return 
            '<x-backend.select-box :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').' :options="$'.$relation_small_plural.'"></x-backend.select-box>';
    }

    public function getFormElementMultipleSelect($name,$nullable,$relation){
        $relation_small_plural = Str::plural(strtolower($relation));
        $selected_relation_small_plural = '$selected'.$relation_small_plural;
        return
            '<x-backend.multiple-select :name="\''.$name.'\'" '.($nullable ? '' : 'required="1"').' :options="$'.$relation_small_plural.'" :selected="'.$selected_relation_small_plural.'"></x-backend.select-box>';
    }

    public function setResponses()
    {
        $all_model_paths = "";
        $all_relations = "";
        $compact_relations_array = "";
        foreach ($this->columns as $key => $column) {
            if(isset($column["name"]) && !empty($column["name"]) && isset($column["relation"])){
                $relation = ucfirst($column["relation"]);
                $relation_small = strtolower($column["relation"]);
                $relation_small_plural = Str::plural($relation_small);
                $relation_plural = Str::plural($relation);
                $all_model_paths .= 'use App\Models\\'.$relation_plural.'\\'.$relation.";\n";
                $compact_relations_array .= "'".$relation_small_plural."', ";
                $all_relations .= '$'.$relation_small_plural.'= collect('.$relation.'::all()->toArray())->mapWithKeys(function ($item) {
                    return [$item[\'id\'] => $item[\'name\']];
                });';
            }
        }
        $this->all_model_paths = $all_model_paths;
        $this->all_relations = $all_relations;
        $this->compact_relations_array = $compact_relations_array;
    }
}
