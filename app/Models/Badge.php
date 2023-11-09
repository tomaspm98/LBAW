<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $table = 'badge';
    protected $primaryKey = 'badge_id';

    public $timestamps = false;

    protected $fillable = [
        'badge_name',
        'badge_description'
    ];
}
