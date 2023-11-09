<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    use HasFactory;

    protected $table = 'moderator';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'tag_id');
    }
}
