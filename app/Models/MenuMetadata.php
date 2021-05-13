<?php

namespace App\Models;

use Carbon\Carbon;
use Egal\Model\Model as EgalModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id {@validation-rules required|string|unique:menu_metadata,id} {@property-type filed}
 * @property string $name {@validation-rules required|string|unique:menu_metadata,name} {@property-type filed}
 * @property string $icon {@validation-rules string|nullable|max:255} {@property-type filed}
 * @property Carbon $created_at {@property-type filed}
 * @property Carbon $updated_at {@property-type filed}
 *
 * @action getMetadata {@statuses-access guest,logged}
 * @action getItem {@statuses-access guest, logged}
 * @action getItems {@roles-access developer}
 * @action create {@roles-access developer}
 * @action update {@roles-access developer}
 * @action delete {@roles-access developer}
 */
class MenuMetadata extends EgalModel
{

    protected $keyType = 'string';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = ['created_at', 'updated_at'];

    protected $fillable = [
        'id',
        'name',
        'icon'
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(MenuEntryMetadata::class);
    }

    public function entriesTree(): HasMany
    {
        return $this->hasMany(MenuEntryMetadata::class)
            ->whereNull('parent_menu_entry_metadata_id')
            ->with('parentsTree');
    }

}
