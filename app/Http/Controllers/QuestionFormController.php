<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizForm;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserQuizEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Mockery\Undefined;
use Nette\Utils\Strings;
use Ramsey\Uuid\Type\Integer;
use Termwind\Components\Dd;

use function GuzzleHttp\Promise\all;

class QuestionFormController extends Controller
{

    public function allQuestion(String $id = null)
    {
        $allQuestion = Question::where('quiz_form_id', $id)->get(['name'])->all();

        return $allQuestion;
    }

    public function index()
    {
        $questionForm = QuizForm::where('user_id', Auth::id())->with('Questions')->get();

        return Inertia::render('Index',  [
            'allQuizForms' => QuizForm::where('user_id', Auth::id())->get(),
            'allQuestions' => $questionForm
        ]);
    }


    public function addQuestion(Request $request)
    {

        $validated = $request->validate(
            [
                'quizFormName' => 'required|max:255',
                'quizQuestions.*.question' => 'required|max:255',
                'quizQuestions.*.answers.*' => 'required|max:255'
            ],
            [
                'quizFormName.required' => 'Please fill Question Form name field',
                'quizQuestions.*.question.required' => 'Please fill Question name field',
                'quizQuestions.*.answers.*.required' => 'Please fill Answers field',
            ]
        );

        $questionForm = new QuizForm();
        $questionForm->user_id = Auth::id();
        $questionForm->name = $request['quizFormName'];
        $questionForm->link = fake()->regexify('[a-z]{3}[0-8]{3}');
        $questionForm->save();

        foreach ($request['quizQuestions'] as $key => $value) {
            // dd($QuestionForm);
            $question = new Question();
            $question->quiz_form_id = $questionForm->id;
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
        $allQuestionsFormsName = QuizForm::where('user_id', Auth::id())->where('id', $id)->get(['name']);
        $allQuestions = Question::where('quiz_form_id', $id)->get();
        $allAnswers = Question::where('quiz_form_id', $id)->with('Answer')->get();


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

        $questionForm = QuizForm::where('link', $id)->get();
        $questionsName = Question::where('quiz_form_id', $questionForm[0]['id'])->get();
        $answers = Question::where('quiz_form_id', $questionForm[0]['id'])->with('answer')->get();
        $quizFormId = $questionForm[0]['id'];
        $userAnswers = UserQuizEntry::where('quiz_form_id', $quizFormId)->get();

        //validating if user made Quiz not working
        foreach ($userAnswers as $key => $value) {
            if ($userAnswers[$key]['user_id'] == Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        }


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
        // dd(Auth::user()['name']);

        $userEntry = new UserQuizEntry();
        $userEntry->user_id = Auth::id();
        $userEntry->quiz_form_id = $request['questionsName'][0]['quiz_form_id'];
        $userEntry->name = Auth::user()['name'];
        $userEntry->save();

        foreach ($request['questionsName'] as $key => $value) {
            $questionsName = Question::where('id', $request['questionsName'][$key]['id'])->get();
            $answerForm = new UserAnswer();
            $answerForm->user_quiz_entry_id = $userEntry['id'];
            $answerForm->name = $value['name'];
            $answerForm->correct_answer = $questionsName[0]['correct_answer'];
            if ($value['correct_answer'] == null) {
                $answerForm->user_answer = '3';
            }else{
                $answerForm->user_answer =  $value['correct_answer'];
            }
            $answerForm->save();
        }

        return Redirect::route('index');
    }

    public function showResults($id)
    {

        $userId = QuizForm::where('id', $id)->get('user_id');

        // dd($userId->count());

        if ($userId->count() == 0) {
            abort(403, 'Unauthorized action.');
        }elseif($userId[0]['user_id'] !== Auth::id()){
            abort(403, 'Unauthorized action.');
        }


        // DB::enableQueryLog();
        // $questionForm = QuizForm::where('user_id', Auth::id())->with('Questions')->where('id', $id)->with('userAnswers')->with('user')->get();

        $questionForm = UserQuizEntry::where('quiz_form_id', $id)->with('userAnswers')->with('user')->get();
        // $questionForm = User::where('id', Auth::id())->get();
        // dd(DB::getQueryLog());

        // dd($questionForm);


        return Inertia::render('QuizResults',  [
            // 'allQuizForms' => QuizForm::where('user_id', Auth::id())->get(),
            'allQuestions' => $questionForm
        ]);
    }



    // public function show()
    // {
    //     $userComments = User::findOrFail('1')->questionForms()->get(['name']);
    //     return view('index', compact('userComments'));
    // }

}
