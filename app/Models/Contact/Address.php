<?php

namespace App\Models\Contact;

use App\Models\Contact\Settings\DataType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
