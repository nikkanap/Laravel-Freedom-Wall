<div class='reply'>
    <div class='post-row'>
        @if ($post->deleted) 
            <em>[deleted]</em>
        @else 
            <strong>{{ $post->username }}</strong>: {{ $post->content }}

            @if ($isLoggedIn) 
                <a href="#" class="reply-btn" onclick="toggleReply({{ $post->id }}); return false;">Reply</a>    
            @endif
            
            @if ($isLoggedIn && $sessionUserId !== null && $post->user_id == $sessionUserId)
                <a href="{{ url('/delete-post/' . $post->id) }}" class="delete-btn" title="Delete">
                    {{--ignore these if may error kay ulol it javascript vscode extension ko lol --}}
                    <i class="fa fa-trash"></i>
                </a>
            @endif
        @endif
    </div>

    @if ($isLoggedIn && !$post->deleted) 
        <div id='reply-form-{{ $post->id }}' style='display:none; margin-top:8px;'>
            <form action="{{url('/post_message')}}" method='POST' style='display:flex; gap:8px; align-items:center;'>
                @csrf
                <input type='hidden' name='parent_id' value={{ $post->id }}>
                <input type='text' name='message' placeholder='Write a reply...' required style='width:80%; padding:5px;'>
                <button type='submit'>Reply</button>
            </form>
        </div>
    @endif

    @if (isset($replies[$post->id])) 
        <div class='thread'>
            @foreach ($replies[$post->id] as $reply) 
                @include('partials.thread', [
                    'post' => $reply,
                    'replies' => $replies,
                    'isLoggedIn' => $isLoggedIn,
                    'sessionUserId' => $sessionUserId
                ])        
            @endforeach
        </div>
    </div>
@endif
