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
 * @property bool $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DataType|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tel whereValue($value)
 *
 * @mixin \Eloquent
 */
class Tel extends Model
{
    protected $table = 'contacts_tel';

    protected $fillable = [
        'type_id',
        'value',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
