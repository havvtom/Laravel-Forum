@extends ('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-2">	
			<div class="page-header">
				<avatar-form :user="{{$profileUser}}"></avatar-form>
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