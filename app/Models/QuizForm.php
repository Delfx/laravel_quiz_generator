<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizForm extends Model
{

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    use HasFactory;
}
