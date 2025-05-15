@extends('Email::layout')
@section('content')

    <div class="b-container">
        <div class="b-panel">
            <p>{!! nl2br($messageData)!!}</p>
            <br>
        </div>
    </div>
@endsection
