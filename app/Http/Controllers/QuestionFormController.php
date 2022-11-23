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


        // $question = new Question;

        // $question->name = $request->name;

        // $question->save();

        // $flight->name = $request->name;

        // $flight->save();
        // $request->validate([
        //     'question' => 'required|min:2|max:12',
        // ]);

        // $validator = Validator::make($request->all(), [
        //     'questions.*.question' => 'min:2|max:255',
        // ]);

        // if ($validator->fails()) {
        //     return Redirect::route('quiz')
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // } else {
        //     dd($request);
        // };

        // dd(Auth::id());
        // $data = array(
        //     "title" => "hello",
        //     "description" => "test test test"
        //   );

        // foreach ($questions as $data) {
        //     dd($questions);
        // };

        $QuestionForm = new QuestionForm();

        $QuestionForm->user_id = Auth::id();

        $QuestionForm->name = $request['questionsFormName'];

        $QuestionForm->save();

        // foreach ($request as $key => $value) {
        //     // dd($request['questionsFormName']);


        // }
    }
}
