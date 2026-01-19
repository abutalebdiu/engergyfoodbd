<?php

namespace App\Models;

use App\Models\Setting\SupportTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportMessage extends Model
{
    public function ticket(){
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id', 'id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
