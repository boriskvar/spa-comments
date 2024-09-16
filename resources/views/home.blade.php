@extends('layout')

@section('content')
    <h1>Home</h1>

    <div id="app">
        <app :comments="{{ \App\Models\Comment::all() }}"></app>
    </div>

@endsection
