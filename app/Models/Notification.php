<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo(Member::class, 'notification_user');
    }
}
