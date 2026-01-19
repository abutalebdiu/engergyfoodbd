<?php

namespace App\Models\Mail;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;


    protected $guarded = ["id"];


    public function category()
    {
        return $this->belongsTo(Category::class, 'group_id', 'id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PENDING) {
                $html = '<span class="badge bg-warning">' . trans("Pending") . '</span>';
            } elseif ($this->status == Status::CONTINUE) {
                $html = '<span class="badge bg-primary">' . trans("Continue") . '</span>';
            } elseif ($this->status == Status::COMPLETED) {
                $html = '<span class="badge bg-success">' . trans("Successful") . '</span>';
            }

            return $html;
        });
    }
}
