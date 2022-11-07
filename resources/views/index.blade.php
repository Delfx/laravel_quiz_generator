@extends('layouts.quiz')

@section('content')
    <div class="container">

        @foreach ($userComments as $userComment)
            <h1>
                {{ $userComment->name }}
            </h1>
        @endforeach

        @push('scripts')
            @vite('resources/js/Quiz/quiz.js')
        @endpush
    </div>
@endsection
