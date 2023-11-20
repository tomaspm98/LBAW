<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        'content_is_visible',
        'tsvectors '
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
        return $this->answers()->where('content_is_visible', true)->count();
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

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->content_creation_date)->diffForHumans();
    }

    public function scopeFilter($query, array $filters)
    {   
        // Search by tag
        $query->when(isset($filters['tag']), function ($query) use ($filters) {
            $tag = $filters['tag'];
            
            if ($tag != 'all') { // Tag selected
                $query->when($filters['tag'] ?? false, function ($query) use ($tag) {
                    $query->whereHas('tag', function ($query) use ($tag) {
                        $query->where('tag_name', $tag);
                    });
                });
            }
        });

        // Search by total or partial words without distinction of uppercase and lowercase letters
        $query->when(isset($filters['search']), function ($query) use ($filters) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search) {
                $query->whereRaw('tsvectors @@ plainto_tsquery(?)', "%$search%")
                    ->orWhereRaw('LOWER(question_title) LIKE ?', "%$search%")
                    ->orWhereRaw('LOWER(content_text) LIKE ?', "%$search%");
            });
        });

        // Sorting by creation date
        if (isset($filters['orderBy']) && $filters['orderBy'] === 'date') {
            $orderDirection = $filters['orderDirection'] === 'asc' ? 'asc' : 'desc';
            $query->orderBy('content_creation_date', $orderDirection);
        }
    }

}