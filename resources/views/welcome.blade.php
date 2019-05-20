@extends('layouts.main')

@section('content')
    <div class="container-fluid light">

        <div class="row pt-3">
            <div class="col-lg-2 pr-lg-0">
                <sort-component
                        class="mb-3"
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </sort-component>
                <tags-component
                        class="mb-3"
                        api_token="{{ $api_token }}"
                        tags_route="{{ route('tags.index') }}">
                </tags-component>
                <circles-component
                        class="mb-3"
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}"
                        users_route="{{ route('users.index') }}">
                </circles-component>
                <state-component
                        class="mb-3"
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </state-component>
            </div>

            <!-- main col -->
            <div class="col">
                <posts-component
                        api_token="{{ $api_token }}"
                        posts_route="{{ route('posts.index') }}"
                        post_view_route="{{ route('post', '') }}"
                        answers_route="{{ route('answers.index') }}"
                        feed_route="{{ route('feed') }}">
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

