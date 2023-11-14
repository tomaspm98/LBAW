<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'member';
    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'user_email',
        'user_password',
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
        return $this->hasMany(Notification::class, 'user_id');
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

}

