<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Models\Contact\Address;
use App\Models\Contact\Category;
use App\Models\Contact\Company;
use App\Models\Contact\CustomField;
use App\Models\Contact\Date;
use App\Models\Contact\Email;
use App\Models\Contact\Settings\DataType;
use App\Models\Contact\SocialMedia;
use App\Models\Contact\Source;
use App\Models\Contact\Tel;
use App\Models\Contact\Website;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'contact_id')->orderBy('is_primary', 'DESC');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'contact_id')->orderBy('is_primary', 'DESC');
    }

    public function tels(): HasMany
    {
        return $this->hasMany(Tel::class, 'contact_id')->orderBy('is_primary', 'DESC');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(DataType::class, 'contacts_categories', 'contact_id', 'type_id')->where('model', '=', Category::class);
    }

    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(DataType::class, 'contacts_sources', 'contact_id', 'type_id')->where('model', '=', Source::class);
    }

    public function socialMedia(): HasMany
    {
        return $this->hasMany(SocialMedia::class, 'contact_id', 'id');
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class, 'contact_id', 'id');
    }

    public function dates(): HasMany
    {
        return $this->hasMany(Date::class, 'contact_id', 'id');
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class, 'contact_id', 'id');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'contact_id', 'id')->orderBy('is_primary', 'DESC');
    }

    public function primaryCompany(): HasOne
    {
        return $this->companies()->one()->where('is_primary', true);
    }

    public function employeeCompanies(): hasManyThrough
    {
        return $this->hasManyThrough(Contact::class, Company::class, 'contact_id', 'id', 'id', 'company_contact_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Company::class, 'company_contact_id')->orderBy('is_primary', 'DESC');
    }

    public function employeeContacts(): HasManyThrough
    {
        return $this->hasManyThrough(Contact::class, Company::class, 'company_contact_id', 'id', 'id', 'contact_id');
    }

    public function scopePerson(Builder $query): void
    {
        $query->where('type', '=', ContactType::Person->value);
    }

    public function scopeCompany(Builder $query): void
    {
        $query->where('type', '=', ContactType::Person->value);
    }

    public function primaryAddress(): HasOne
    {
        return $this->addresses()->one()->where('is_primary', true);
    }

    public function primaryEmail(): HasOne
    {
        return $this->emails()->one()->where('is_primary', true);
    }

    public function primaryTel(): HasOne
    {
        return $this->tels()->one()->where('is_primary', true);
    }


}
