<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note_share extends Model
{
    protected $table = 'note_shares';
    protected $fillable = ['email', 'note_id', 'is_read_only'];
}
