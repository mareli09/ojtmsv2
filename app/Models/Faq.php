<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;

    protected $fillable = ['faculty_id', 'category', 'question', 'answer'];

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
}
