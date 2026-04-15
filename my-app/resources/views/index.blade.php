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

@forelse($posts as $post)
    <div class="reply">
        <div class="post-row">
            @if($post->deleted)
                <em>[deleted]</em>
            @else
                <strong>{{ $post->user->username }}</strong>: {{ $post->content }}

                @if($isLoggedIn)
                    <a href="#" class="reply-btn"
                       onclick="var f = document.getElementById('reply-form-{{ $post->id }}');
                                f.style.display = f.style.display === 'none' ? 'block' : 'none';
                                return false;">
                        Reply
                    </a>
                @endif

                @if($isLoggedIn && $sessionUserId == $post->user_id)
                    <form action="{{ url('/delete-post/' . $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this post?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            @endif
        </div>

        @if($isLoggedIn && !$post->deleted)
            <div id="reply-form-{{ $post->id }}" style="display:none; margin-top:8px;">
                <form action="{{ url('/post_message') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $post->id }}">
                    <textarea name="message" placeholder="Write a reply..." required
                              style="width:100%; height:50px; font-size:13px;"></textarea>
                    <button type="submit" style="margin-top:4px;">Reply</button>
                </form>
            </div>
        @endif

        {{-- Threaded replies (recursive) --}}
        @if($post->replies->count() > 0)
            @include('partials.reply', ['replies' => $post->replies])
        @endif
    </div>
@empty
    <p class="empty-state">No messages yet. Be the first to post!</p>
@endforelse

<div class="pagination">
    @if($posts->previousPageUrl())
        <a href="{{ $posts->previousPageUrl() }}" class="btn-onboard">Previous</a>
    @endif

    @if($posts->nextPageUrl())
        <a href="{{ $posts->nextPageUrl() }}" class="btn-onboard">Next</a>
    @endif
</div>

</body>
</html>