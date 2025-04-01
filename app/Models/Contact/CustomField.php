<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $type_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DataType|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomField whereValue($value)
 *
 * @mixin \Eloquent
 */
class CustomField extends Model
{
    protected $table = 'contacts_custom_fields';

    protected $fillable = [
        'type_id',
        'value',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
