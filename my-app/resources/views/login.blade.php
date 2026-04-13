<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/log-reg.css">
</head>
<body>

    <div id="main-container">
        <p id="card-eyebrow">AENS Freedom Board</p>
        <p id="card-title">Welcome Back</p>
        <p id="card-sub">Log in to your account to continue.</p>

        @if ($errors->any()) 
            <p class='error'>{{ $errors->first() }}</p>
        @endif
        
        <form id="field" method="post" action="/login">
            @csrf
            <label for="username" autocomplete="off">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button id="btn-submit" type="submit">Login</button>
        </form>

        <p id="card-footer">Don't have an account? <a href="{{ url('/register') }}">Register here</a></p>
        <a id="home-link" href="{{ url('/index') }}">Home</a>
    </div>
</body>
</html>
