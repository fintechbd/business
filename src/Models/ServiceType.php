<?php

namespace Fintech\Business\Models;

use Fintech\Core\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceType extends Model implements HasMedia
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

    protected $casts = ['service_type_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool'];

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
    /**
     * @return HasOne
     */
    public function serviceTypeParent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'service_type_parent_id');
    }

    /**
     * @return HasMany
     */
    public function serviceTypeChild(): HasMany
    {
        return $this->hasMany(self::class, 'service_type_parent_id', 'id');
    }


    /**
     * @return HasOne
     */
    public function serviceTypeGrandParent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'service_type_parent_id')
            ->with('serviceTypeGrandParent');
    }

    /**
     * @return HasMany
     */
    public function serviceTypeGrandChild(): HasMany
    {
        return $this->hasMany(self::class, 'service_type_parent_id', 'id')
            ->with('serviceTypeGrandChild');
    }

    /**
     * @return HasOne
     */
    public function allParentAccounts(): HasOne
    {
        return $this->serviceTypeParent()->with('allParentAccounts');
    }

    /**
     * @return HasMany
     */
    public function allChildAccounts(): HasMany
    {
        return $this->serviceTypeChild()->with('allChildAccounts');
    }

    /**
     * @return array
     */
    public function getAllParentListAttribute(): array
    {
        $data = array();
        $parentList = $this->allParentAccounts ? $this->allParentAccounts->toArray() : null;
        if(!empty($parentList)){
            $data = [$parentList['id'] => $parentList['service_type_name']];
            if(isset($parentList['all_parent_accounts'])):
                $data = array_merge($data, $this->all_accounts($parentList['all_parent_accounts']));
            endif;
            sort($data);
        }
        return  $data;
    }

    /**
     * @return array
     */
    public function getAllChildListAttribute(): array
    {
        $data = array();
        $childLists = $this->allChildAccounts->toArray();
        foreach ($childLists as $childList):
            $data[] = $childList;
            if(isset($childList['all_child_accounts'])):
                $data = array_merge($data, $this->all_account_children($childList['all_child_accounts']));
            endif;
        endforeach;
        return  $data;
    }

    /**
     * @param $input
     * @return array
     */
    public function all_accounts($input): array
    {
        $data = array();
        $data = [$input['id'] => $input['service_type_name']];
        if(isset($input['all_parent_accounts'])):
            $data = array_merge($data,$this->all_accounts($input['all_parent_accounts']));
        endif;
        return $data;
    }

    /**
     * @param $input
     * @return array
     */
    public function all_account_children($input): array
    {
        $data = array();
        foreach ($input as $inputs):
            $data[] = $inputs;
            if(isset($inputs['all_child_accounts'])):
                $data = array_merge($data,$this->all_account_children($inputs['all_child_accounts']));
            endif;
        endforeach;
        return $data;
    }

    /**
     * @return HasMany
     */
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

    /**
     * @return array
     */
    public function getLinksAttribute()
    {
        $primaryKey = $this->getKey();

        $links = [
            'show' => action_link(route('business.service-types.show', $primaryKey), __('core::messages.action.show'), 'get'),
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
