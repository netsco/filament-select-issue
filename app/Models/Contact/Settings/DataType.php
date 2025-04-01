<?php

namespace App\Models\Contact\Settings;

use App\Enums\ContactDataType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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
