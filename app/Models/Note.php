<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use UuidsTrait;

    public $incrementing = false;
    protected $fillable = ['title', 'contents'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shares()
    {
        return $this->hasMany(Note_share::class);
    }
}
