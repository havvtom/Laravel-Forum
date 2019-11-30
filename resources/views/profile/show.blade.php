@extends ('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-2">	
			<div class="page-header">
				<h1>
					{{$profileUser->name}}
					<small>since {{$profileUser->created_at->diffForHumans()}}</small>
				</h1>
			</div>
			<div>
			
					@foreach($threads as $thread)
					<div class="card">
		                <div class="card-header">
		                	<div class="level">
		                		<span class="flex"><h5><a href="{{route('profile', $profileUser->name)}}">{{$profileUser->name}}</a> posted <a href="{{route ('thread', [$thread->channel->slug, $thread->id])}}}}">	{{$thread->title}}</a></h5></span>
		                		<span>{{$thread->created_at->diffForHumans()}}</span>
		                	</div>
		                </div>

		                <div class="card-body">
		                   {{$thread->body}}
		                </div>
		                </div>	
		             @endforeach
		          
		          {{$threads->links()}}
		    </div>
		</div>
	</div>
</div>	

@endsection