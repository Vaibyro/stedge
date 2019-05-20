@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        <div class="row pt-3">
            <div class="col">
                    <post-component
                            :id="{{ $id }}"
                            :coeff="1"
                            :full_display="true"
                            posts_route="{{ route('posts.index') }}"
                            answers_route="{{ route('answers.index') }}"
                            api_token="{{ $api_token }}">
                    </post-component>
            </div>
        </div>

    </div>
@endsection

