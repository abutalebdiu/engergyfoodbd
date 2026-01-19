<?php

namespace App\Traits;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait GlobalStatus
{
    public static function changeStatus($id, $column = 'status')
    {
        $modelName = get_class();
        $query     = $modelName::findOrFail($id);
        if ($query->$column == Status::ACTIVE) {
            $query->$column = Status::INACTIVE;
        } else {
            $query->$column = Status::ACTIVE;
        }
        $message       = keyToTitle($column). ' changed successfully';

        $query->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }


    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::ACTIVE) {
            $html = '<span class="badge bg-success">' . trans('Enabled') . '</span>';
        } else {
            $html = '<span class="badge bg-warning">' . trans('Disabled') . '</span>';
        }
        return $html;
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', Status::INACTIVE);
    }
}
