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

                <div class="pane mb-2">
                    <form>
                        <textarea class="form-control"></textarea>
                        <button class="btn btn-gd-primary">Envoyer</button>
                    </form>
                </div>

                <div class="pane mb-2">
                    <p><b>noobmaster69</b></p>
                    <p>#Voiture #Urgent</p>
                    <p>Les gars j'ai un problème je suis coincé sur l'autoroute !
                        <a href="#"><span class="badge badge-gd-primary">Voir</span></a>
                    </p>
                    <div class="text-right mb-3">
                        Il y a 2 heures
                    </div>

                    <div>
                        <div class="pane-answer pane-best-answer mb-2">
                            <div><b>xXxmEGAStorm (il y a 1 heure)</b></div>
                            Débrouille-toi poto
                        </div>
                        <div class="pane-answer mb-2">
                            <div><b>noobmaster69 (il y a 37 minutes)</b></div>
                            T'es méchant
                        </div>
                        <div class="pane-answer mb-2">
                            <div><b>noobmaster69 (il y a 37 minutes)</b></div>
                            lorem ipsum
                        </div>
                        <div class="pane-answer mb-2">
                            <div><b>noobmaster69 (il y a 37 minutes)</b></div>
                            lorem ipsum
                        </div>
                        <div class="pane-answer mb-2">
                            <div><b>noobmaster69 (il y a 37 minutes)</b></div>
                            lorem ipsum
                        </div>
                        <div>
                            <input type="text" class="form-control"/>
                            <button class="btn btn-primary">Répondre</button>
                        </div>
                    </div>

                </div>

                <div class="pane mb-2">
                    <p><b>noobmaster69</b></p>
                    <p>Que pensez-vous des moutons nains ?

                    </p>

                    <div class="mb-2">
                        <a href="#"><span class="btn btn-gd-primary">Super cool</span></a>
                        <a href="#"><span class="btn btn-gd-primary">Nul</span></a>
                    </div>
                    <div class="text-right">
                        Il y a 6 heures
                    </div>
                </div>

                <div class="pane mb-2">
                    <p><b>noobmaster69</b></p>
                    <p>Lorem <b>ipsum</b> dolor sit amet,
                        adipiscing
                        elit.
                        Fusce consequat porta orci, et molestie nisi commodo...
                        <a href="#"><span class="badge badge-gd-primary">Lire la suite</span></a>
                    </p>
                    <div class="text-right">
                        Hier
                    </div>
                </div>
                <div class="pane mb-2">
                    <p><b>noobmaster69</b></p>
                    <p>Lorem <b>ipsum</b> dolor sit amet,
                        adipiscing
                        elit.
                        Fusce consequat porta orci, et molestie nisi commodo...
                        <a href="#"><span class="badge badge-gd-primary">Lire la suite</span></a>
                    </p>
                    <div class="text-right">
                        Hier
                    </div>
                </div>


            </div>


        </div>
@endsection
