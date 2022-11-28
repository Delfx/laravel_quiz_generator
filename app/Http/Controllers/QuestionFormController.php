<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionForm;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Nette\Utils\Strings;
use Ramsey\Uuid\Type\Integer;
use Termwind\Components\Dd;

use function GuzzleHttp\Promise\all;

class QuestionFormController extends Controller
{

    public function allQuestion(String $id = null)
    {
        $allQuestion = Question::where('question_form_id', $id)->get(['name'])->all();

        return $allQuestion;
    }

    public function index()
    {
        $questionForm = QuestionForm::where('user_id', Auth::id())->with('Questions')->get();

        return Inertia::render('Index',  [
            'allQuestionsFormsName' => QuestionForm::where('user_id', Auth::id())->get(),
            'allQuestions' => $questionForm
        ]);
    }


    public function addQuestion(Request $request)
    {

        $validated = $request->validate(
            [
                'questionsFormName' => 'required|max:255',
                'questions.*.question' => 'required|max:255',
                'questions.*.answers.*' => 'required|max:255'
            ],
            [
                'questionsFormName.required' => 'Please fill Question Form name field',
                'questions.*.question.required' => 'Please fill Question name field',
                'questions.*.answers.*.required' => 'Please fill Answers field',
            ]
        );

        $questionForm = new QuestionForm();
        $questionForm->user_id = Auth::id();
        $questionForm->name = $request['questionsFormName'];
        $questionForm->link = fake()->regexify('[a-z]{3}[0-8]{3}');
        $questionForm->save();

        foreach ($request['questions'] as $key => $value) {
            // dd($QuestionForm);
            $question = new Question();
            $question->question_form_id = $questionForm->id;
            $question->name = $value['question'];
            $question->correct_answer = $value['trueAnswer'];
            $question->save();

            // dd($value['answers']);
            foreach ($value['answers'] as $key => $formAnswer) {
                $answer = new Answer();
                $answer->question_id = $question->id;
                $answer->name = $formAnswer;
                $answer->save();
                // dd($answer);
            }
        }

        $allQuestionsFormsName = $questionForm::where('user_id', Auth::id())->get(['name'])->all();

        return Redirect::route('index');
    }

    public function editQuestion($id, Request $request)
    {
        $allQuestionsFormsName = QuestionForm::where('user_id', Auth::id())->where('id', $id)->get(['name']);
        $allQuestions = Question::where('question_form_id', $id)->get();
        $allAnswers = Question::where('question_form_id', $id)->with('Answer')->get();


        // return Inertia::render('Quiz', [

        //     'allQuestionsFormsName' => $allQuestionsFormsName,
        //     'allQuestions' => $this->allQuestion($QuestionForm->id)

        // ]);


        // with('Questions')->get();



        // $validated = $request->validate(
        //     [
        //         'questionsFormName' => 'required|max:255',
        //         'questions.*.question' => 'required|max:255',
        //         'questions.*.answers.*' => 'required|max:255'
        //     ],
        //     [
        //         'questionsFormName.required' => 'Please fill Question Form name field',
        //         'questions.*.question.required' => 'Please fill Question name field',
        //         'questions.*.answers.*.required' => 'Please fill Answers field',
        //     ]
        // );

        // dd($allQuestions);


        return Inertia::render('EditQuiz', [
            'questions' => $allQuestions
        ]);
    }

    public function showQuestion($id)
    {

        $questionForm = QuestionForm::where('link', $id)->get();
        $questionsName = Question::where('question_form_id', $questionForm[0]['id'])->get();
        $answers = Question::where('question_form_id', $questionForm[0]['id'])->with('answer')->get();
        $userAnswers = UserAnswer::get();

        foreach ($userAnswers as $key => $value) {
            if ($userAnswers[$key]['user_id'] == Auth::id()) {
                dd($userAnswers[$key]['user_id']);
            }
        }

        // dd($answers);


        return Inertia::render('ShowQuiz', [
            'formName' => $questionForm[0]['name'],
            'questionsName' => $questionsName,
            'answers' => $answers
        ]);
    }

    public function addAnswer(Request $request)
    {


        // $questionsName = Question::where('id', $request['questionsName'][1]['id'])->get('correct_answer');

        // dd($questionsName[0]['correct_answer']);
        // dd($request['questionsName'][0]['id']);




        foreach ($request['questionsName'] as $key => $value) {
            // dd($key);

            $questionsName = Question::where('id', $request['questionsName'][$key]['id'])->get();



            $answerForm = new UserAnswer();
            $answerForm->user_id = Auth::id();
            $answerForm->question_form_id = $value['question_form_id'];
            $answerForm->name = $value['name'];
            $answerForm->correct_answer = $questionsName[0]['correct_answer'];
            $answerForm->user_answer =  $value['correct_answer'];
            $answerForm->save();

        }

        return Redirect::route('index');
    }



    // public function show()
    // {
    //     $userComments = User::findOrFail('1')->questionForms()->get(['name']);
    //     return view('index', compact('userComments'));
    // }

}
