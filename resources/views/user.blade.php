@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        <div class="pane mb-3">
            <a href="{{ route('root') }}">
                <font-awesome-icon icon="chevron-left"></font-awesome-icon>
                Retour</a>
        </div>

        <div class="pane mb-3">
            <div class="text-center my-4">
                <img class="border rounded-circle" src="{{ $user->avatarUrl() }}" style="width: 180px;">
            </div>
            <hr>
            <div class="text-center">
                <h2>{{ $user->firstname }} <span class="bold">{{ $user->lastname }}</span></h2>
                <h4><font-awesome-icon icon="at"></font-awesome-icon>{{ $user->name }}</h4>
                {{ $user->posts->count() }} post(s) - {{ $user->answers->count() }} réponse(s)
            </div>
        </div>

        <posts-component
                api_token="{{ $api_token }}"
                posts_route="{{ route('posts.index') }}"
                post_view_route="{{ route('post', '') }}"
                answers_route="{{ route('answers.index') }}"
                users_info_route="{{ route('user', '') }}"
                likes_route="{{ route('likes.index') }}"
                feed_route="{{ route('feed') }}"
                :display_posting="false"
                user_filter="{{ $user->id }}"
        ></posts-component>

    </div>
@endsection

