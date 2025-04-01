<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Date extends Model
{
    protected $table = 'contacts_dates';

    protected $fillable = [
        'type_id',
        'value',
        'notes',
    ];

    protected $casts = [
        'value' => 'date',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
