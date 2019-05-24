@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        <div class="pane mb-3">
            <a href="{{ route('root') }}">
                <font-awesome-icon icon="chevron-left"></font-awesome-icon>
                Accueil</a>
        </div>

        <new-circle-component
            class="mb-3"
            api_token="{{ $api_token }}"
            circles_route="{{ route('circles.index') }}"
        ></new-circle-component>


    @foreach ($circles as $circle)
            <div class="mb-3">
                <circle-management-component
                        api_token="{{ $api_token }}"
                        circles_route="{{ route('circles.index') }}"
                        users_route="{{ route('users.index') }}"
                        circle_id="{{$circle->id}}"
                        user_id="{{ $user_id }}"
                ></circle-management-component>
            </div>
        @endforeach
    </div>
@endsection