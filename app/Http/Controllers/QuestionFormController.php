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
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


use function GuzzleHttp\Promise\all;

class QuestionFormController extends Controller
{

    public function allQuestion(String $id = null)
    {
        $allQuestion = Question::where('quiz_form_id', $id)->get(['name'])->all();

        return $allQuestion;
    }

    public function index(Request $request)
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
        $questionForm = UserQuizEntry::where('quiz_form_id', $request['questionsName'][0]['quiz_form_id'])->get();



        if ($questionForm->isEmpty()) {

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
                if ($value['correct_answer'] === null) {
                    $answerForm->user_answer = '3';
                } else {
                    $answerForm->user_answer =  $value['correct_answer'];
                }
                $answerForm->save();
            }

            return Redirect::route('index');
        } elseif ($questionForm[0]['user_id'] == Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function showResults($id)
    {

        $userId = QuizForm::where('id', $id)->get('user_id');

        if ($userId->count() == 0) {
            abort(403, 'Unauthorized action.');
        } elseif ($userId[0]['user_id'] !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $questionForm = UserQuizEntry::where('quiz_form_id', $id)->with('userAnswers')->with('user')->get();

        return Inertia::render('QuizResults',  [
            'allQuestions' => $questionForm
        ]);
    }

    public function showQuizAnswerDetails(Request $request, $userId, $quizId)
    {
        $answerForm = UserQuizEntry::where('quiz_form_id', $quizId)->where('user_id', $userId)->with('userAnswers')->with('quizForm')->with('user')->get();
        $answers = Question::where('quiz_form_id', $answerForm[0]['quiz_form_id'])->with('answer')->get();

        return Inertia::render('QuizDetailResults', [
            // 'formName' => $questionForm[0]['name'],
            'questionForm' => $answerForm,
            'answers' => $answers,
            'previous_url' => $request->session()->get('_previous')['url']
        ]);
    }

    public function deleteQuestion(Request $request, $id)
    {

        $quizForm = QuizForm::where('id', $id);
        $userQuizEntries = UserQuizEntry::where('quiz_form_id', ($quizForm->get())[0]['id']);

        foreach ($userQuizEntries->get() as $key => $value) {
            $userAnswers = UserAnswer::where('user_quiz_entry_id', $value['id'])->delete();
        }

        $userQuizEntries->delete();
        $questions = Question::where('quiz_form_id', ($quizForm->get())[0]['id']);

        foreach ($questions->get() as $key => $value) {
            $answers = Answer::where('question_id', $value['id'])->delete();
        }
        
        $questions->delete();
        $quizForm->delete();
    }

    public function deleteAnswer($id)
    {
        $userQuizEntries = UserQuizEntry::where('id', $id);
        $userAnswers = UserAnswer::where('user_quiz_entry_id', (($userQuizEntries->get())[0]['id']))->delete();
        $userQuizEntries->delete();
    }
}
