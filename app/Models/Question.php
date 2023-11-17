<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'question'; // Specify your table name here
    protected $primaryKey = 'question_id'; // Set the primary key

    public $timestamps = false; // Set this to false if you are handling timestamps like creation_date manually

    protected $fillable = [
        'question_title',
        'question_tag',
        'correct_answer',
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

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'question_tag');
    }

    public function correctAnswer()
    {
        return $this->belongsTo(Answer::class, 'correct_answer');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function getAnswerCountAttribute()
    {
        return $this->answers()->count();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'vote_content_question');
    }

    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'content_reported_question');
    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count();
    }

    public function follows()
    {
        return $this->hasMany(UserFollowQuestion::class, 'question_id');
    }


}