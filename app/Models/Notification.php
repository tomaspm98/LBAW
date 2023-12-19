<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
        'notification_user',
        'notification_content',
        'notification_date',
        'notification_is_read',
        'notification_type'
    ];

    public $timestamps = false;

    public function author()
    {
        return $this->belongsTo(Member::class, 'notification_user');
    }
    public function is_unread($notification_id)
    {
        $notification = Notification::find($notification_id);
        if ($notification->notification_is_read == 0) {
            return true;
        } else {
            return false;
        }
    }
    
}
