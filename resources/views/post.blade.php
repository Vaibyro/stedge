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
                api_token="{{ $api_token }}">
        </post-component>

    </div>
@endsection

