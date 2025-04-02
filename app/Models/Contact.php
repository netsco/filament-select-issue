<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Models\Contact\Email;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'company',
        'title',
        'first_name',
        'last_name',
        'job_title',
        'background_info',
    ];

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'type' => ContactType::class,
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->type) {
                ContactType::Person => $this->title . ' ' . $this->first_name . ' ' . $this->last_name,
                ContactType::Company => $this->company,
            }
        );
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'contact_id')->orderBy('is_primary', 'DESC');
    }

    public function primaryEmail(): HasOne
    {
        return $this->emails()->one()->where('is_primary', true);
    }


}
