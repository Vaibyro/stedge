@extends('layouts.main')

@section('content')
    <div class="container-fluid light">
        <div class="row pt-3">
            <div class="col-lg-2 pr-lg-0">
            </div>

            <div class="col">

                    <post-component
                            :id="{{ $id }}"
                            :coeff="1"
                            :full_display="true"
                            posts_route="{{ route('posts.index') }}"
                            answers_route="{{ route('answers.index') }}"
                            api_token="{{ $api_token }}">
                    </post-component>

                {{$id}}

            </div>

            <div class="col-lg-2 pl-lg-0">
            </div>
        </div>

    </div>
@endsection

