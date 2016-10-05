@extends('layouts.app')

@section('content')

    <a href="/" style="float:right; padding-left: 10px">Exit</a>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>
        <tr class="info">
            <td>Login</td>
            <td>Browser</td>
            <td>IP address</td>
            <td>Entrance</td>
        </tr>
        </thead>
        <tbody>
        @foreach($histories as $history)
            <tr>
                <td>{{$user->email}}</td>
                <td>{{$history->browser}}</td>
                <td>{{$history->ip}}</td>
                <td>{{$history->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
