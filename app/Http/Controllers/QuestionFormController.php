<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\all;

class QuestionFormController extends Controller
{

    public function show()
    {

        $userComments = User::findOrFail('1')->questionForms()->get(['name']);

        // dd($userComments);

        return view('index', compact('userComments'));
    }

    public function addQuestion(Request $request)
    {


        // dd($request['questionsFormName']);

        $validated = $request->validate([
            'questionsFormName' => 'required|max:255',
        ]);

        if ($request['questionsFormName'] == null) {
            return inertia('Quiz', ['isFilled' => ('Fill all fields')]);
        }else{
            $QuestionForm = new QuestionForm();
            $QuestionForm->user_id = Auth::id();
            $QuestionForm->name = $request['questionsFormName'];
            $QuestionForm->save();
        }

        return Redirect::route('index');
        // foreach ($request as $key => $value) {
        //     // dd($request['questionsFormName']);


        // }
    }
}
