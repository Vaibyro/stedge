@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 pr-lg-0">
                <div class="sticky-top lateral-col">
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
                </div>
            </div>

            <!-- main col -->
            <div class="col">
                <posts-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}"
                        posts_route="{{ route('posts.index') }}"
                        post_view_route="{{ route('post', '') }}"
                        answers_route="{{ route('answers.index') }}"
                        likes_route="{{ route('likes.index') }}"
                        feed_route="{{ route('feed') }}"
                        users_route="{{ route('users.index') }}"
                        tags_route="{{ route('tags.index') }}"
                        users_info_route="{{ route('user', '') }}"
                ></posts-component>
            </div>

            <div class="col-lg-2 pl-lg-0">
                <div class="sticky-top lateral-col">
                <state-component
                        class="mb-3"
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}">
                </state-component>
                <trends-component
                        api_token="{{ $api_token }}"
                        user_id="{{ $user_id }}"
                        trends_route="{{ route('trends') }}"
                ></trends-component>
                </div>
            </div>
        </div>
    </div>
@endsection

