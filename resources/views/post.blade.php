@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        <div class="pane mb-3">
            <a href="{{ route('root') }}">
                <font-awesome-icon icon="chevron-left"></font-awesome-icon>
                Retour</a>
        </div>

        <post-component
                :id="{{ $id }}"
                :coeff="1"
                :full_display="true"
                posts_route="{{ route('posts.index') }}"
                answers_route="{{ route('answers.index') }}"
                api_token="{{ $api_token }}"

                posts_route="{{ route('posts.index') }}"
                post_view_route="{{ route('post', '') }}"
                likes_route="{{ route('likes.index') }}"
                users_info_route="{{ route('user', '') }}"
        ></post-component>

    </div>
@endsection

