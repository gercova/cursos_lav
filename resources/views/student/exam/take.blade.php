@extends('layouts.app')

@section('title', 'Examen - ' . $attempt->exam->course->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $attempt->exam->title }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ $attempt->exam->course->title }}</p>
                </div>
                <div class="text-right">
                    <div id="timer" class="text-xl font-bold text-red-600"></div>
                    <div class="text-sm text-gray-500">Tiempo restante</div>
                </div>
            </div>
        </div>

        <form id="exam-form" action="{{ route('exam.submit', $attempt->exam->course_id) }}" method="POST">
            @csrf
            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-8">
                    @foreach($questions as $index => $question)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ $index + 1 }}
                                </span>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        {{ $question->question }}
                                    </h3>

                                    <div class="space-y-3">
                                        @foreach($question->options as $key => $option)
                                            <div class="flex items-center">
                                                <input type="radio"
                                                       id="question_{{ $question->id }}_{{ $key }}"
                                                       name="answers[{{ $question->id }}]"
                                                       value="{{ $key }}"
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                                <label for="question_{{ $question->id }}_{{ $key }}"
                                                       class="ml-3 block text-sm font-medium text-gray-700">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Finalizar Examen
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let timeRemaining = {{ $attempt->time_remaining }};

    function updateTimer() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;

        document.getElementById('timer').textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeRemaining <= 0) {
            document.getElementById('exam-form').submit();
        } else {
            timeRemaining--;
            setTimeout(updateTimer, 1000);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTimer();

        // Prevenir que el usuario recargue la página o cierre la pestaña
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
    });
</script>
@endsection
