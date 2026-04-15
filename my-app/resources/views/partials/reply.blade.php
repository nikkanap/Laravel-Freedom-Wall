<div class="thread">
    @foreach($replies as $reply)
        <div class="post-row" style="margin: 6px 0;">
            @if($reply->deleted)
                <em>[deleted]</em>
            @else
                <strong>{{ $reply->user->username }}</strong>: {{ $reply->content }}

                @if($isLoggedIn)
                    <a href="#" class="reply-btn"
                       onclick="var f = document.getElementById('reply-form-{{ $reply->id }}');
                                f.style.display = f.style.display === 'none' ? 'block' : 'none';
                                return false;">
                        Reply
                    </a>
                @endif

                @if($isLoggedIn && $sessionUserId == $reply->user_id)
                    <form action="{{ url('/delete-post/' . $reply->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this reply?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            @endif
        </div>

        {{-- Reply form --}}
        @if($isLoggedIn && !$reply->deleted)
            <div id="reply-form-{{ $reply->id }}" style="display:none; margin-top:4px;">
                <form action="{{ url('/post_message') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                    <textarea name="message" placeholder="Reply to {{ $reply->user->username }}..." required
                              style="width:100%; height:50px; font-size:13px;"></textarea>
                    <button type="submit" style="margin-top:4px;">Reply</button>
                </form>
            </div>
        @endif

        {{-- Recursion: if this reply has its own replies, include myself again --}}
        @if($reply->replies->count() > 0)
            @include('partials.reply', ['replies' => $reply->replies])
        @endif
    @endforeach
</div>