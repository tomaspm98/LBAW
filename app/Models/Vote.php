<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'vote';
    protected $primaryKey = 'vote_id';

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo(Member::class, 'vote_author');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'vote_content_question');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'vote_content_answer');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'vote_content_comment');
    }
}
