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
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property ContactType $type
 * @property string|null $company
 * @property string|null $title
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $display_name
 * @property string|null $job_title
 * @property string|null $background_info
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Address> $additionalAddresses
 * @property-read int|null $additional_addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Email> $additionalEmails
 * @property-read int|null $additional_emails_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tel> $additionalTels
 * @property-read int|null $additional_tels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DataType> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $companies
 * @property-read int|null $companies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CustomField> $customFields
 * @property-read int|null $custom_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Date> $dates
 * @property-read int|null $dates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Email> $emails
 * @property-read int|null $emails_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Contact> $employeeCompanies
 * @property-read int|null $employee_companies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Contact> $employeeContacts
 * @property-read int|null $employee_contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read Address|null $primaryAddress
 * @property-read Company|null $primaryCompany
 * @property-read Email|null $primaryEmail
 * @property-read Tel|null $primaryTel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $relatedEvents
 * @property-read int|null $related_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SocialMedia> $socialMedia
 * @property-read int|null $social_media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DataType> $sources
 * @property-read int|null $sources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tel> $tels
 * @property-read int|null $tels_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Website> $websites
 * @property-read int|null $websites_count
 *
 * @method static Builder<static>|Contact company()
 * @method static Builder<static>|Contact newModelQuery()
 * @method static Builder<static>|Contact newQuery()
 * @method static Builder<static>|Contact onlyTrashed()
 * @method static Builder<static>|Contact person()
 * @method static Builder<static>|Contact query()
 * @method static Builder<static>|Contact whereBackgroundInfo($value)
 * @method static Builder<static>|Contact whereCompany($value)
 * @method static Builder<static>|Contact whereCreatedAt($value)
 * @method static Builder<static>|Contact whereDeletedAt($value)
 * @method static Builder<static>|Contact whereDisplayName($value)
 * @method static Builder<static>|Contact whereFirstName($value)
 * @method static Builder<static>|Contact whereId($value)
 * @method static Builder<static>|Contact whereJobTitle($value)
 * @method static Builder<static>|Contact whereLastName($value)
 * @method static Builder<static>|Contact whereTitle($value)
 * @method static Builder<static>|Contact whereType($value)
 * @method static Builder<static>|Contact whereUpdatedAt($value)
 * @method static Builder<static>|Contact withTrashed()
 * @method static Builder<static>|Contact withoutTrashed()
 *
 * @mixin \Eloquent
 */
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
            get: fn () => match ($this->type) {
                ContactType::Person => $this->title.' '.$this->first_name.' '.$this->last_name,
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

    public function additionalAddresses(): HasMany
    {
        return $this->hasMany(Address::class, 'contact_id')->where('is_primary', false);
    }

    public function additionalEmails(): HasMany
    {
        return $this->hasMany(Email::class, 'contact_id')->where('is_primary', false);
    }

    public function additionalTels(): HasMany
    {
        return $this->HasMany(Tel::class, 'contact_id')->where('is_primary', false);
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'notes_contacts');
    }

    public function events(): HasMany
    {
        return $this->HasMany(Event::class, 'client_id');
    }

    public function relatedEvents(): HasMany
    {
        // TODO link in other stuff (events_contacts, venues)
        return $this->HasMany(Event::class, 'client_id');
    }

    public function tasks(): MorphToMany
    {
        return $this->morphToMany(Task::class, 'link', 'tasks_links');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'contact_id', 'id');
    }
}
