<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tag';
    protected $primaryKey = 'tag_id';

    public $timestamps = false;

    protected $fillable = [
        'tag_name',
        'tag_description'
    ];

    public function moderator()
    {
        return $this->belongsToMany(Question::class, 'usertag', 'user_id', 'tag_id');
    }

    public function question()
    {
        return $this->belongsToMany(Question::class, 'questiontag', 'question_id', 'tag_id');
    }
}