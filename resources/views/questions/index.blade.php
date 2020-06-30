@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>All Questions</h2>
                        <div class="ml-auto">
                            <a href="{{ route('questions.create') }}" class="btn btn-outline-secondary">
                                Ask a Question
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    @include('layouts._messages')

                    @isset($questions)
                    @foreach ($questions as $question)
                    <div class="media">
                        <div class="d-flex flex-column counters">
                            <div class="vote">
                                <strong>{{ $question->votes_count }}</strong>
                                {{ str_plural('vote',$question->votes_count) }}
                            </div>
                            <div class="status {{ $question->status }}">
                                <strong>{{ $question->answers_count }}</strong>
                                {{ str_plural('answers_count',$question->answers_count) }}
                            </div>
                            <div class="view">
                                {{ $question->views ." ". str_plural('view',$question->views) }}
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="d-flex align-items-center">
                                <h3 class="mt-0"><a href="{{ $question->url }}">{{ $question->title }}</a></h3>
                                <div class="ml-auto">
                                    {{-- @if(Auth::user()->can('update', $question)) --}}
                                    {{-- or --}}
                                    @can('update', $question)
                                    <a href="{{ route('questions.edit', $question->id) }}"
                                        class="btn btn-sm btn-outline-info">Edit</a>
                                    @endcan

                                    {{-- @if(Auth::user()->can('delete', $question)) --}}
                                    {{-- or --}}
                                    @can('delete', $question)
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                        class="form-delete">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>

                            <p class="lead">
                                Asker by: <a href="$question->user->url">{{ $question->user->name }}</a>
                                <small class="text-muted">{{ $question->created_date }}</small>
                            </p>
                            <div class="excerpt">
                                {{ $question->excerpt }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    @endisset

                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
