<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answer';
    protected $primaryKey = 'answer_id';

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'content_author',
        'content_creation_date',
        'content_text',
        'content_is_edited',
        'content_is_visible'
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(Member::class, 'content_author');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'answer_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'vote_content_answer');
    }

    public function getVoteCountAttribute()
    {
        $upVotes = $this->votes()->where('upvote', 'up')->count();
        $downVotes = $this->votes()->where('upvote', 'down')->count();

        return $upVotes - $downVotes; 
    }

    public function userVote()
    {
        return $this->hasOne(Vote::class, 'vote_content_answer')->where('vote_author', Auth::id());
    }

    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getIsCorrectAttribute()
    {
        return $this->question->correct_answer == $this->answer_id;
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'content_reported_answer');
    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count();
    }
}