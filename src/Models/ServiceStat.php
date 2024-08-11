<?php

namespace Fintech\Business\Models;

use Fintech\Auth\Models\Role;
use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Traits\AuditableTrait;
use Fintech\MetaData\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceStat extends BaseModel
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

    protected $casts = ['service_stat_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool'];

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
        return $this->belongsTo(config('fintech.business.service_model', Service::class));
    }

    public function destinationCountry(): BelongsTo
    {
        return $this->belongsTo(config('fintech.metadata.country_model', Country::class), 'destination_country_id');
    }

    public function sourceCountry(): BelongsTo
    {
        return $this->belongsTo(config('fintech.metadata.country_model', Country::class), 'source_country_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(config('fintech.auth.role_model', Role::class));
    }

    public function serviceVendor(): BelongsTo
    {
        return $this->belongsTo(config('fintech.business.service_vendor_model', ServiceVendor::class), 'service_vendor_id');
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
            'show' => action_link(route('business.service-stats.show', $primaryKey), __('restapi::messages.action.show'), 'get'),
            'update' => action_link(route('business.service-stats.update', $primaryKey), __('restapi::messages.action.update'), 'put'),
            'destroy' => action_link(route('business.service-stats.destroy', $primaryKey), __('restapi::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('business.service-stats.restore', $primaryKey), __('restapi::messages.action.restore'), 'post'),
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
