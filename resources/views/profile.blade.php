@extends('layouts.main')
@section('content')
    <div class="container-fluid light">
        <div class="pane mb-3">
            <a href="{{ route('root') }}">
                <font-awesome-icon icon="chevron-left"></font-awesome-icon>
                Accueil</a>
        </div>

        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="pane mb-3">
            <div class="text-center my-4">
                <img class="border rounded-circle" src="{{ $user->avatarUrl() }}" style="width: 180px;">

                <div class="row justify-content-center">
                    <form action="{{ route('profile') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" class="form-control-file" name="avatar" id="avatarFile" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <h2>{{ $user->firstname }} <span class="bold">{{ $user->lastname }}</span></h2>
                <h4><font-awesome-icon icon="at"></font-awesome-icon>{{ $user->name }}</h4>
                {{ $user->posts->count() }} post(s) - {{ $user->answers->count() }} réponse(s)
            </div>
        </div>

    </div>
@endsection






