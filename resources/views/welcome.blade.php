@extends('layouts.main')

@section('content')
    <div class="container-fluid light">

        <div class="row pt-3">
            <div class="col-lg-2 pr-lg-0">
                <sort-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </sort-component>
                <tags-component
                        api_token="{{ $api_token }}"
                        tags_route="{{ route('tags.index') }}">
                </tags-component>
                <circles-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}"
                        users_route="{{ route('users.index') }}">
                </circles-component>
                <state-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </state-component>
            </div>

            <!-- main col -->
            <div class="col">
                <posts-component
                        api_token="{{ $api_token }}"
                        posts_route="{{ route('posts.index') }}"
                        answers_route="{{ route('answers.index') }}">
                </posts-component>
            </div>

            <div class="col-lg-2 pl-lg-0">
                <trends-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </trends-component>
            </div>
        </div>
    </div>
@endsection

