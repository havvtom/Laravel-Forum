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

				@can('update', $profileUser)

					<form method="POST" action="{{route('avatar', $profileUser)}}" enctype="multipart/form-data">
						@csrf
						<input type="file" name="avatar">

						<button class="btn btn-primary">Add Avatar</button>
					</form>

				@endcan

				<img src="{{asset('/storage/'.$profileUser->avatar())}}" width="50" height="50">
			</div>
			<div>
			
					@forelse($activities as $date => $records)
						<h3 class="page-header">{{$date}}</h3>
						@foreach($records as $activity)
		                	@include("profile.activities.{$activity->type}")
		                @endforeach
		               @empty
		               <p>There are no activities for this user at the moment</p>
		            @endforelse
		          
		         
		    </div>
		</div>
	</div>
</div>	

@endsection