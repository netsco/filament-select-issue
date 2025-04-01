<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DataType|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Source whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Source extends Model
{
    protected $table = 'contacts_sources';

    protected $fillable = [
        'type_id',
        'value',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
