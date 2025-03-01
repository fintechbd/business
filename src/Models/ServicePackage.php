<?php

namespace Fintech\Business\Models;

use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Traits\Audits\BlameableTrait;
use Fintech\MetaData\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ServicePackage extends BaseModel implements Auditable
{
    use BlameableTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $translatable = ['name'];

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $casts = ['service_package_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool', 'blocked' => 'bool'];

    protected $hidden = ['creator_id', 'editor_id', 'destroyer_id', 'restorer_id'];
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(config('fintech.business.service_model', Service::class), 'service_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('fintech.metadata.country_model', Country::class));
    }
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
            'show' => action_link(route('business.service-packages.show', $primaryKey), __('core::messages.action.show'), 'get'),
            'update' => action_link(route('business.service-packages.update', $primaryKey), __('core::messages.action.update'), 'put'),
            'destroy' => action_link(route('business.service-packages.destroy', $primaryKey), __('core::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('business.service-packages.restore', $primaryKey), __('core::messages.action.restore'), 'post'),
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
