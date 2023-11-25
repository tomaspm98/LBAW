<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $primaryKey = 'report_id';

    public $timestamps = false;

    protected $fillable = [
        'report_creator',
        'report_reason',
        'content_reported_question',
        'content_reported_answer',
        'content_reported_comment',
        'report_reason',
        'report_text',
        'report_date',
    ];

    public function creator()
    {
        return $this->belongsTo(Member::class, 'report_creator');
    }

    public function handler()
    {
        return $this->belongsTo(Moderator::class, 'report_handler');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'content_reported_question');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'content_reported_answer');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'content_reported_comment');
    }

    
}
