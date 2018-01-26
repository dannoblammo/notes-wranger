<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use UuidsTrait;

    public $incrementing = false;
    protected $fillable = ['title', 'contents', 'last_modified_by_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastModifiedByUser()
    {
        return $this->belongsTo(User::class, 'last_modified_by_user_id');
    }

    public function shares()
    {
        return $this->hasMany(Note_share::class);
    }
}
