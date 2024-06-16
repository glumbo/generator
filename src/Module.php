<?php

namespace Glumbo\Generator;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $fillable = ['view_permission_id', 'name', 'url', 'created_by', 'module', 'updated_by'];

    /**
     * Action Button Attribute to show in grid
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return "<div class=\"btn-group action-btn\">
                {$this->getDeleteButtonAttribute("delete-module", "admin.modules.destroy")}
                </div>";
    }

    public function getEditButtonAttribute($permission, $route)
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" class="btn btn-outline-primary">
                    <i data-toggle="tooltip" data-placement="top" title="'._tr('edit').'" class="bi bi-pencil"></i>
                </a>';
        }
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute($permission, $route)
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" 
                    class="btn btn-outline-danger" data-method="delete"
                    data-trans-button-cancel="'._tr('buttons.general.cancel').'"
                    data-trans-button-confirm="'._tr('buttons.general.crud.delete').'"
                    data-trans-title="'._tr('strings.backend.general.are_you_sure').'">
                        <i data-toggle="tooltip" data-placement="top" title="'._tr('delete').'" class="bi bi-trash"></i>
                </a>';
        }
    }
}
