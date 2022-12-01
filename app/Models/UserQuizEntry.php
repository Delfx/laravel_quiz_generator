<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizEntry extends Model
{

    /**
     * Get all of the comments for the UserQuizEntry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quizForm()
    {
        return $this->belongsTo(QuizForm::class);
    }


    use HasFactory;
}
