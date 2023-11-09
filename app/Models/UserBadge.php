<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    protected $table = 'userbadge';
    protected $primaryKey = 'userbadge_id';

    public $timestamps = false;

    protected $fillable = [
        'userbadge_date'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }
}
