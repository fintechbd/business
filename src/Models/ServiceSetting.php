<?php

namespace Fintech\Business\Models;

use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSetting extends BaseModel
{
    use AuditableTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $casts = ['service_setting_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool'];

    protected $hidden = ['creator_id', 'editor_id', 'destroyer_id', 'restorer_id'];

    protected $attributes = ['service_setting_rule' => 'string|nullable|max:255|min:1'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * @return array
     */
    public function getLinksAttribute()
    {
        $primaryKey = $this->getKey();

        $links = [
            'show' => action_link(route('business.service-settings.show', $primaryKey), __('restapi::messages.action.show'), 'get'),
            'update' => action_link(route('business.service-settings.update', $primaryKey), __('restapi::messages.action.update'), 'put'),
            'destroy' => action_link(route('business.service-settings.destroy', $primaryKey), __('restapi::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('business.service-settings.restore', $primaryKey), __('restapi::messages.action.restore'), 'post'),
        ];

        if ($this->getAttribute('deleted_at') == null) {
            unset($links['restore']);
        } else {
            unset($links['destroy']);
        }

        return $links;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
