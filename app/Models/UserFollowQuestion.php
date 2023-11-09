<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollowQuestion extends Model
{
    use HasFactory;

    protected $table = 'userfollowquestion';
    public $incrementing = false; // Assuming this table doesn't have an auto-increment primary key
    public $timestamps = false;

    protected $fillable = [
        'follow'
    ];

    public function user()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
