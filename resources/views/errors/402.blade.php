@extends('errors.base-message')

@section('error-message')
    <h1>402 - Payment Required</h1>
    @if (Request::route()->named('kitchen.index'))
        <p>This content is purchasable.<br> To purchase the kitchen module, please contact:</p>
    @elseif(Request::route()->named('waiter.index'))
        <p>This content is purchasable. <br>To purchase the waiter module, please contact:</p>
    @else
        <p>This content is purchasable. To purchase, please contact:</p>
    @endif
@endsection
