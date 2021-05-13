<?php

namespace App\Models;

use App\Enums\InterfaceMetadataType;
use Carbon\Carbon;
use Egal\Exception\ValidateException;
use Egal\Model\Model as EgalModel;
use Illuminate\Support\Facades\Validator;

/**
 * @property string $id {@validation-rules required|string|unique:interface_metadata}
 * @property string $name {@validation-rules required|string|unique:interface_metadata}
 * @property string $type {@validation-rules required|string}
 * @property string $data {@validation-rules required|string}
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
class InterfaceMetadata extends EgalModel
{

    protected $keyType = 'string';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
        'id',
        'name',
        'type',
        'data'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function (InterfaceMetadata $interfaceMetadata) {
            if ($interfaceMetadata->isDirty('data')) {
                $validator = Validator::make(
                    json_decode($interfaceMetadata->data, true),
                    $interfaceMetadata->getDataValidationRules()
                );
                if ($validator->fails()) {
                    $exception = new ValidateException('Data incorrect!');
                    $exception->setErrors($validator->errors()->toArray());
                    throw $exception;
                }
            }
        });
    }

    protected function getDataValidationRules(): array
    {
        switch ($this->type) {
            case InterfaceMetadataType::TABLE:
                return [
                    'label' => 'required|string',
                    'model_service_name' => 'required|string',
                    'model_name' => 'required|string',
                    'default_per_page' => 'required|numeric',

                    'default_sort' => 'required|array',
                    'default_sort.field' => 'required|string',
                    'default_sort.direction' => 'required|string|in:asc,desc',

                    'fields' => 'required|array',
                    'fields.*.name' => 'required|string',
                    'fields.*.label' => 'required|string',
                    'fields.*.visible' => 'required|boolean',
                    'fields.*.sortable' => 'required|boolean',

                    'filters' => 'required|array',
                    'filters.*.label' => 'required|string',
                    'filters.*.type' => 'required|string|in:text,selector',
                    'filters.*.by_field' => 'required|array|min:1',
                ];
            default:
                return [];
        }
    }

}
