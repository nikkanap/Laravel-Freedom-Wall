<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AENS Freedom Board</title>

    <link rel="stylesheet" href="{{ asset('css/index.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

@if(!$isLoggedIn)
    <header>
        <h1>AENS Freedom Board</h1>
        <p>Freely express your mind.</p>
    </header>

    <section class="onboarding">
        <a class="btn-onboard" href="{{ url('/register') }}">Register</a>
        <a class="btn-onboard" href="{{ url('/login') }}">Login</a>
    </section>

    <h2>Recent Messages</h2>

@else
    <header style="flex-direction: row">
        <h1 style="margin-bottom: 13px">
            <a href="{{ url('/') }}" style="text-decoration:none; color:inherit;">
                AENS Freedom Board
            </a>
        </h1>

        <div style="display:flex; align-items:center; gap:12px;">
            <span class="username">
                Logged in as <strong>{{ $username }}</strong>
            </span>

            <a href="#"
               id="logout"
               onclick="document.getElementById('logout-form').submit(); return false;">
                Logout
            </a>

            <form id="logout-form" action="/logout" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </header>

    <form class="post-form" action="{{ url('/post_message') }}" method="POST">
        @csrf
        <textarea placeholder="Write a message..." required name="message"></textarea>

        <div class="post-form-footer">
            <button type="submit">Post to Board</button>
        </div>
    </form>

    <h2>Recent Messages</h2>
@endif

@if(empty($topPosts))
    <p class="empty-state">No messages yet. Be the first to post!</p>
@endif

@foreach($topPosts as $post)
    <div class="reply">
        <div class="post-row">

            @if($post->deleted)
                <em>[deleted]</em>
            @else
                <strong>{{ $post->username }}</strong>: {{ $post->content }}

                @if($isLoggedIn)
                    <a href="#" class="reply-btn">Reply</a>
                @endif

                @if($isLoggedIn && $sessionUserId == $post->user_id)
                    <a href="{{ url('/delete-post/' . $post->id) }}" class="delete-btn">
                        <i class="fa fa-trash"></i>
                    </a>
                @endif
            @endif

        </div>
    </div>
@endforeach

<div class="pagination">
    @if($page > 1)
        <a href="{{ url('/?page=' . ($page - 1)) }}" class="button">Previous</a>
    @endif

    @if($page < $totalPages)
        <a href="{{ url('/?page=' . ($page + 1)) }}" class="button">Next</a>
    @endif
</div>

</body>
</html>