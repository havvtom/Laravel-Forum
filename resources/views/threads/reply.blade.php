<div class="card" id="reply-{{$reply->id}}">
    <div class="card-header">
    	<div class="level">
    		<h5 class="flex">
    		<a href="{{route('profile', $reply->user->name)}}">{{$reply->user->name}}</a> said {{$reply->created_at->diffForHumans()}}
    		</h5>
    		<div>

    			<form method="POST" action="/replies/{{$reply->id}}/favorites">
    				@csrf
    				<button type="submit" class="btn btn-secondary" {{$reply->isFavorited()? 'disabled' : ''}}>{{$reply->favorites_count}} {{Str::plural('Favorite', $reply->favorites_count)}} </button>
    			</form>
    		</div>
    	</div>
       
    </div>
    <div class="card-body">
      {{$reply->body}}
    </div>
    @can('update', $reply)
    <div class="card-footer">
        <button class="btn btn-xs">Edit</button>
        <form method="POST" action="{{route('delete_reply', [$reply->id])}}">
            @csrf
            @method("DELETE")
            <button class="btn btn-danger btn-xs">Delete</button>
        </form>
    </div>
    @endcan
</div>