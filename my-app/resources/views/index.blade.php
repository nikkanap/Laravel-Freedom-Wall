<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AENS Freedom Board</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    @if (!$isLoggedIn) 
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
            <h1 style="margin-bottom: 13px">AENS Freedom Board</h1>
            <div style="display:flex; align-items:center; gap:12px;">
                <span class="username">Logged in as <strong>{{ $username }}</strong></span>
                <form method="POST" action="/logout">
                    @csrf
                    <button id="logout" type="submit">Logout</button>
                </form>
            </div>
        </header>
        
        <form class="post-form" action="{{ url('/post_message') }}" method="POST">
            @csrf
            <textarea placeholder="Write a message..." required name="message"></textarea>
            <div class="post-form-footer">
                <button type="submit" name="submit" value="submit">Post to Board</button>
            </div>
        </form>

        <h2>Recent Messages</h2>
    @endif

    @if (empty($topPosts))
        <p class="empty-state">No messages yet. Be the first to post!</p>
    @endif

    @foreach ($topPosts as $post)
        @include('partials.thread', [
            'post' => $reply,
            'replies' => $replies,
            'isLoggedIn' => $isLoggedIn,
            'sessionUserId' => $sessionUserId,
            'username' => $username
        ])   
    @endforeach

    <div class="pagination">
        @if ($page > 1)
            <a href="{{ url('/?page=' . ($page - 1)) }}" class="button">Previous</a>
        @endif

        @if ($page < $totalPages)
            <a href="{{ url('/?page=' . ($page + 1)) }}" class="button">Next</a>
        @endif
    </div>


    <script>
        function toggleReply(postId) {
            var form = document.getElementById('reply-form-' + postId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</body>
</html>