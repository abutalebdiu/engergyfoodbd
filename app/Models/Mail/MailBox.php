<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailBox extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function mail_domain()
    {
        return $this->belongsTo(DomainConfig::class, 'domain', 'id');
    }
}
