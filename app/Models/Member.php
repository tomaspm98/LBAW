<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Authenticatable 
{

    use HasApiTokens, HasFactory, Notifiable, AuthenticatableTrait;

    protected $table = 'member';
    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $casts = [
        'user_birthdate' => 'timestamp',
        'user_creation_date' => 'timestamp',
    ];

    protected $fillable = [
        'username',
        'user_email',
        'password',
        'picture',
        'user_birthdate',
        'user_creation_date',
        'user_score'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'notification_user');
    }
    public function unreadNotifications()
    {
        return $this->notifications()->where('notification_is_read', false);
    }

    public function readNotifications()
    {
        return $this->notifications()->where('notification_is_read', true);
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class, 'content_author');
    }

    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'content_author');
    }

    public function getAnswerCountAttribute()
    {
        return $this->answers()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'content_author');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function badges()
{
    return $this->belongsToMany(Badge::class, 'userbadge', 'user_id', 'badge_id');
}

    public function follows(){
        return $this->hasMany(UserFollowQuestion::class, 'user_id')->where('follow', true);
    }

}

