@extends('exercise::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('exercise.name') !!}</p>
@endsection
