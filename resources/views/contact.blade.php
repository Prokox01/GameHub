<!-- resources/views/szablon.blade.php -->

@extends('layouts.app')
<style type="text/css">
    .container{
        color:white !important;
    }
    </style>
@section('content')
    <div class="container">
        <h1>Formularz kontaktowy</h1>

        <form action="" method="post">
            @csrf

            <div class="form-group">
                <label for="name">Imię:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="subject">Temat:</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="message">Treść wiadomości:</label>
                <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Wyślij</button>
        </form>
    </div>
@endsection
