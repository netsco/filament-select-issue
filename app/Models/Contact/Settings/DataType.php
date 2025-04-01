<?php

namespace App\Models\Contact\Settings;

use App\Enums\ContactDataType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property ContactDataType $model
 * @property string $value
 * @property string|null $colour
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static>|DataType newModelQuery()
 * @method static Builder<static>|DataType newQuery()
 * @method static Builder<static>|DataType ofModel(\App\Enums\ContactDataType $dataType)
 * @method static Builder<static>|DataType onlyTrashed()
 * @method static Builder<static>|DataType query()
 * @method static Builder<static>|DataType whereColour($value)
 * @method static Builder<static>|DataType whereCreatedAt($value)
 * @method static Builder<static>|DataType whereDeletedAt($value)
 * @method static Builder<static>|DataType whereId($value)
 * @method static Builder<static>|DataType whereModel($value)
 * @method static Builder<static>|DataType whereUpdatedAt($value)
 * @method static Builder<static>|DataType whereValue($value)
 * @method static Builder<static>|DataType withTrashed()
 * @method static Builder<static>|DataType withoutTrashed()
 *
 * @mixin \Eloquent
 */
class DataType extends Model
{
    use SoftDeletes;

    protected $table = 'contact_data_types';

    protected $fillable = [
        'model',
        'value',
        'colour',
    ];

    protected $casts = [
        'model' => ContactDataType::class,
    ];

    public function scopeOfModel(Builder $query, ContactDataType $dataType): void
    {
        $query->where('model', $dataType);
    }
}
