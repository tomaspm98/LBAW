<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';
    protected $primaryKey = 'comment_id';

    public $timestamps = false;

    protected $fillable = [
        'answer_id',
        'content_author',
        'content_creation_date',
        'content_text',
        'content_is_edited',
        'content_is_visible'
    ];

    public function author()
    {
        return $this->belongsTo(Member::class, 'content_author');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'vote_content_comment');
    }

    public function getVoteCountAttribute()
    {
        $upVotes = $this->votes()->where('upvote', 'up')->count();
        $downVotes = $this->votes()->where('upvote', 'down')->count();

        return $upVotes - $downVotes; 
    }

    public function userVote()
    {
        return $this->hasOne(Vote::class, 'vote_content_comment')->where('vote_author', Auth::id());
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'content_reported_comment');
    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count();
    }

    public function getQuestion()
    {
        return $this->answer->question->question_id;
    }
}
