<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserQuizEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserAnswerController extends Controller
{


    public function allAnswers()
    {
        $allUserAnswers = UserQuizEntry::where('user_id', Auth::id())->with('quizForm')->with('userAnswers')->get();
        
        return Inertia::render('UserQuizResults', [
            'allQuestions' => $allUserAnswers
        ]);
    }
}
