<?php

use App\Http\Controllers\QuestionFormController;
use App\Http\Controllers\UserAnswerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpParser\Builder\Class_;
use PhpParser\Node\Expr\PostDec;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [QuestionFormController::class, 'index'])->name('index');


// Route::get('/quiz', function () {
//     return Inertia::render('Quiz');
// })->middleware(['auth', 'verified'])->name('quiz');

// Route::post('/addQuestion', [QuestionFormController::class, 'addQuestion']);


Route::prefix('/quiz')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Quiz');
    })->middleware(['auth', 'verified']);

    Route::post('/addQuestion', [QuestionFormController::class, 'addQuestion']);

    Route::post('/addAnswer', [QuestionFormController::class, 'addAnswer']);

    Route::delete('/deleteQuestion/{id}', [QuestionFormController::class, 'deleteQuestion'])->name('deleteQuestion');

    Route::get('/editQuestion/{id}', [QuestionFormController::class, 'editQuestion'])->middleware(['auth', 'verified']);

    Route::get('/showQuestion/{id}', [QuestionFormController::class, 'showQuestion'])->middleware(['auth', 'verified']);

    Route::get('/showResults/{id}', [QuestionFormController::class, 'showResults'])->middleware(['auth', 'verified']);

    Route::get('/answerDetailResults/{userId}/{quizId}', [QuestionFormController::class, 'showQuizAnswerDetails'])->middleware(['auth', 'verified'])->name('showQuizAnswerDetails');


});


Route::prefix('/user')->group(function () {

    Route::get('/allAnswers', [UserAnswerController::class, 'allAnswers'])->middleware(['auth', 'verified'])->name('allAnswers');
});


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('hello', function () {
//     return Inertia::render('Hello');
// });

// Route::get('test', [QuestionFormController::class, 'show']);

require __DIR__ . '/auth.php';
