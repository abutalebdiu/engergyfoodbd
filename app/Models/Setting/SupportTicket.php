<?php

namespace App\Models\Setting;

use App\Models\User;
use App\Constants\Status;
use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SupportTicket extends Model
{
    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->name,
        );
    }

    public function username(): Attribute
    {
        return new Attribute(
            get: fn() => $this->email,
        );
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::SUPPORT_OPEN) {
                $html = '<span class="badge bg-success">' . trans("Open") . '</span>';
            } elseif ($this->status == Status::SUPPORT_ANSWER) {
                $html = '<span class="badge bg-primary">' . trans("Answered") . '</span>';
            } elseif ($this->status == Status::SUPPORT_REPLY) {
                $html = '<span class="badge bg-warning">' . trans("Customer Reply") . '</span>';
            } elseif ($this->status == Status::SUPPORT_CLOSE) {
                $html = '<span class="badge bg-dark">' . trans("Closed") . '</span>';
            }
            return $html;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportMessage()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
