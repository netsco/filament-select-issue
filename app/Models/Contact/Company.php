<?php

namespace App\Models\Contact;

use App\Enums\ContactType;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


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
