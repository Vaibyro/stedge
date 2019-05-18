@extends('layouts.main')

@section('content')
    <div class="container-fluid light">

        <div class="row">
            <div class="col-lg-3">
                <div class="p-1  mb-2" style="background-color: #e2ebf0; border-radius: 8px;">
                    <h5>Sujets</h5>
                    <ul>
                        <li><a href="#"># Programmation</a></li>
                        <li><a href="#"># Divers</a></li>
                        <li><a href="#"># Voiture</a></li>
                        <li><a href="#"># Bricolage</a></li>
                        <li><a href="#"># Police</a></li>
                    </ul>
                </div>

                <div class="p-1" style="background-color: #e2ebf0; border-radius: 8px;">
                    <h5>Mes cercles</h5>
                </div>
            </div>

            <div class="col-lg-9 p-3">
                <posts-component></posts-component>
            </div>


        </div>

@endsection

