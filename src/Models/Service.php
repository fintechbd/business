<?php

namespace Fintech\Business\Models;

use Fintech\Core\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use AuditableTrait;
    use InteractsWithMedia;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $appends = ['links'];

    protected $casts = ['service_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool'];

    protected $hidden = ['creator_id', 'editor_id', 'destroyer_id', 'restorer_id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo_png')
            ->acceptsMimeTypes(['image/png'])
            ->singleFile()
            ->useDisk(config('filesystems.default', 'public'));

        $this->addMediaCollection('logo_svg')
            ->acceptsMimeTypes(['image/svg+xml'])
            ->singleFile()
            ->useDisk(config('filesystems.default', 'public'));
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function serviceVendor(): BelongsTo
    {
        return $this->belongsTo(ServiceVendor::class, 'service_vendor_id');
    }

    public function serviceVendors(): BelongsToMany
    {
        return $this->belongsToMany(config('fintech.business.service_vendor_model', ServiceVendor::class), 'service_service_vendor')->withTimestamps();
    }

    public function serviceStat(): HasMany
    {
        return $this->hasMany(ServiceStat::class, 'service_id', 'id');
    }

    public function servicePackage(): HasMany
    {
        return $this->hasMany(ServicePackage::class, 'service_id', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('fintech.auth.role_model', \Fintech\Auth\Models\Role::class),
            'role_service'
        )->withTimestamps();

    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(config('fintech.metadata.country_model', \Fintech\MetaData\Models\Country::class),
            'country_service'
        )->withTimestamps();

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

    public function getLinksAttribute(): array
    {
        $primaryKey = $this->getKey();

        $links = [
            'show' => action_link(route('business.services.show', $primaryKey), __('core::messages.action.show')),
            'update' => action_link(route('business.services.update', $primaryKey), __('core::messages.action.update'), 'put'),
            'destroy' => action_link(route('business.services.destroy', $primaryKey), __('core::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('business.services.restore', $primaryKey), __('core::messages.action.restore'), 'post'),
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
