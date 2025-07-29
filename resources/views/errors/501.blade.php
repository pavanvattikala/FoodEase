@extends('errors.base-message')

@section('error-message')
    {{-- Use the getMessage() method on the exception --}}
    <h1>501 - Module Not Enabled</h1>
    <p>{{ $exception->getMessage() }}</p>
    <a href="/dashboard">Return To Dashboard</a>
@endsection
@section('title', '501 - Module Not Enabled')
