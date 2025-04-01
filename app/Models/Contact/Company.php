<?php

namespace App\Models\Contact;

use App\Enums\ContactType;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $company_contact_id
 * @property string $job_title
 * @property bool $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Contact|null $contact
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Company extends Model
{
    protected $table = 'contacts_companies';

    protected $fillable = [
        'contact_id',
        'company_contact_id',
        'job_title',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class, 'id', 'company_contact_id')->where('type', '=', ContactType::Company);
    }
}
