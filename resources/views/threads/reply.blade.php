<reply :attributes="{{$reply}}" inline-template v-cloak>
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
            </div>
            <div v-else v-text="body">
                {{$reply->body}}   
            </div>
        </div>
        @can('update', $reply)
        <div class="card-footer level">
            <button class="btn btn-info btn-xs mr-1" @click="editing=true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
        </div>
        @endcan
    </div>
</reply>