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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereValue($value)
 *
 * @mixin \Eloquent
 */
class Website extends Model
{
    protected $table = 'contacts_websites';

    protected $fillable = [
        'type_id',
        'value',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
