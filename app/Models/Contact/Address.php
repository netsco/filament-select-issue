<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $type_id
 * @property string|null $address
 * @property string|null $city
 * @property string|null $region
 * @property string|null $postcode
 * @property bool $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $display_comma
 * @property-read mixed $display_nl
 * @property-read DataType|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Address extends Model
{
    protected $table = 'contacts_addresses';

    protected $fillable = [
        'type_id',
        'address',
        'city',
        'region',
        'postcode',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    protected function displayNl(): Attribute
    {
        $address = array_filter([$this->address, $this->city, $this->region, $this->postcode]);

        return Attribute::make(
            get: fn () => implode("\n", $address)
        );
    }

    protected function displayComma(): Attribute
    {
        $address = array_filter([$this->address, $this->city, $this->region, $this->postcode]);

        return Attribute::make(
            get: fn () => implode(', ', $address)
        );
    }

    public function type(): HasOne
    {
        return $this->hasOne(DataType::class, 'id', 'type_id')->where('model', '=', self::class);
    }
}
