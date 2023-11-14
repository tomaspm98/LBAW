<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';
    protected $primaryKey = 'comment_id';

    public $timestamps = false;

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
        return $this->votes()->count();
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'content_reported_comment');
    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count();
    }
}
