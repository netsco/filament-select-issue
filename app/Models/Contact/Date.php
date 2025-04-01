<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $type_id
 * @property \Illuminate\Support\Carbon $value
 * @property string $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DataType|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Date whereValue($value)
 *
 * @mixin \Eloquent
 */
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
