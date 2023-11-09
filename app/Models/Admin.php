<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    public $timestamps = false;
    protected $primaryKey = 'user_id';

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    
}
