<?php

namespace App\Models;

use Carbon\Carbon;
use Egal\Model\Model as EgalModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id {@validation-rules unique:menu_metadata} {@property-type filed}
 * @property string $name {@validation-rules required|string} {@property-type filed}
 * @property string $icon {@validation-rules string|max:255} {@property-type filed}
 * @property string $menu_metadata_id {@validation-rules required|string|exists:menu_metadata,id}
 * @property string $interface_metadata_id {@validation-rules string|nullable|exists:interface_metadata,id}
 * @property string $parent_menu_entry_metadata_id {@validation-rules string|nullable|exists:menu_entry_metadata,id}
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @action getMetadata {@statuses-access guest,logged}
 * @action getItem {@statuses-access guest,logged}
 * @action getItems {@roles-access developer}
 * @action create {@roles-access developer}
 * @action update {@roles-access developer}
 * @action delete {@roles-access developer}
 */
class MenuEntryMetadata extends EgalModel
{

    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name',
        'icon',
        'menu_metadata_id',
        'interface_metadata_id',
        'parent_menu_entry_metadata_id'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = ['created_at', 'updated_at'];

    public function parentsTree(): HasMany
    {
        return $this->hasMany(MenuEntryMetadata::class, 'parent_menu_entry_metadata_id', 'id')
            ->with('parentsTree');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(MenuEntryMetadata::class, 'id', 'parent_menu_entry_metadata_id');
    }

}
