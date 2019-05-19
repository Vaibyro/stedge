@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        @if(auth()->user() != null)
            <main-component
                    api_token="<?= auth()->user()->api_token ?>"
                    user_id="<?= auth()->user()->id ?>">
            </main-component>
        @else
            <main-component
                    api_token="<?= \App\User::find(env('PUBLIC_USER_ID', 2))->api_token ?>"
                    user_id="<?= env('PUBLIC_USER_ID', 2) ?>">
            </main-component>
        @endif
    </div>
@endsection

