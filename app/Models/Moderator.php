<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tag_id'
    ];

    protected $table = 'moderator';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
