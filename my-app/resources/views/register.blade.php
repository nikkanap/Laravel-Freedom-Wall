<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="css/log-reg.css">
    </head>
    <body>
        <div id="main-container">
            <p id="card-eyebrow">AENS Freedom Board</p>
            <p id="card-title">Create Account</p>
            <p id="card-sub">Join the board and start expressing yourself.</p>

            @if ($errors->any()) 
                <p class='error'>{{ $errors->first() }}</p>
            @endif

            <form id="field" method="post" action="/register">
                @csrf
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button id="btn-submit" type="submit">Register</button>
            </form>

        <p id="card-footer">Already have an account? <a href="{{ url('/login') }}">Log in here</a></p>
        <a id="home-link" href="{{ url('/') }}">Home</a>
    </body>
</html>