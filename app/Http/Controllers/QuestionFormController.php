<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionFormController extends Controller
{

    public function show()
    {

        $userComments = User::findOrFail('1')->questionForms()->get(['name']);

        // dd($userComments);

        return view('index', compact('userComments'));
    }


}
