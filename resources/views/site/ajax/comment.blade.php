<li class="media comments-item">
    <div class="media-body">
        <h4 class="media-heading">{{$comment->user_id ? $comment->author->name : $comment->name}} <span class="date">{{$comment->created_at->diffForHumans()}}</span> <span></span></h4>
        <p>{{$comment->text}}</p>
    </div>
</li>
