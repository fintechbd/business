<?php

namespace Fintech\Business\Models;

use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Traits\Audits\BlameableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property mixed $allParentAccounts
 * @property mixed $allChildAccounts
 */
class ServiceType extends BaseModel implements Auditable, HasMedia
{
    use BlameableTrait;
    use InteractsWithMedia;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $casts = ['service_type_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool', 'service_stat_data' => 'array', 'service_data' => 'array'];

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

    public function rootParent() {}

    private function iterateOverCacheServiceTypes(string|int $serviceTypeId): self {}

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function serviceTypeGrandParent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'service_type_parent_id')
            ->with('serviceTypeGrandParent');
    }

    public function serviceTypeGrandChild(): HasMany
    {
        return $this->hasMany(self::class, 'service_type_parent_id', 'id')
            ->with('serviceTypeGrandChild');
    }

    public function allParentAccounts(): HasOne
    {
        return $this->serviceTypeParent()->with('allParentAccounts');
    }

    public function serviceTypeParent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'service_type_parent_id');
    }

    public function allChildAccounts(): HasMany
    {
        return $this->serviceTypeChildren()->with('allChildAccounts');
    }

    public function serviceTypeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'service_type_parent_id', 'id');
    }

    public function getAllParentListAttribute(): array
    {
        $data = [];
        $parentList = $this->allParentAccounts ? $this->allParentAccounts->toArray() : null;
        if (! empty($parentList)) {
            $data = [$parentList['id'] => $parentList['service_type_name']];
            if (isset($parentList['all_parent_accounts'])) {
                $data = array_merge($data, $this->all_accounts($parentList['all_parent_accounts']));
            }
            // sort($data);
        }

        return $data;
    }

    public function all_accounts($input): array
    {
        $data = [$input['id'] => $input['service_type_name']];
        if (isset($input['all_parent_accounts'])) {
            $data = array_merge($data, $this->all_accounts($input['all_parent_accounts']));
        }

        return $data;
    }

    public function getAllChildListAttribute(): array
    {
        $data = [];
        $childLists = $this->allChildAccounts->toArray();
        foreach ($childLists as $childList) {
            $data[] = $childList;
            if (isset($childList['all_child_accounts'])) {
                $data = array_merge($data, $this->all_account_children($childList['all_child_accounts']));
            }
        }

        return $data;
    }

    public function all_account_children($input): array
    {
        $data = [];
        foreach ($input as $inputs) {
            $data[] = $inputs;
            if (isset($inputs['all_child_accounts'])) {
                $data = array_merge($data, $this->all_account_children($inputs['all_child_accounts']));
            }
        }

        return $data;
    }

    public function service(): HasMany
    {
        return $this->hasMany(Service::class, 'service_type_id');
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
            'show' => action_link(route('business.service-types.show', $primaryKey), __('core::messages.action.show')),
            'update' => action_link(route('business.service-types.update', $primaryKey), __('core::messages.action.update'), 'put'),
            'destroy' => action_link(route('business.service-types.destroy', $primaryKey), __('core::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('business.service-types.restore', $primaryKey), __('core::messages.action.restore'), 'post'),
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
