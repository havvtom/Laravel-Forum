@component('profile.activities.activity')

	@slot('heading')
		<span class="flex"><h5><a href="{{route('profile', $profileUser->name)}}">{{$profileUser->name}}</a> 
				published <a href="{{route('thread', [$activity->subjectable->channel->slug, $activity->subjectable->id])}}">{{$activity->subjectable->title}}</a>
			</h5></span>
	@endslot
	@slot('body')
		{{$activity->subjectable->body}}
	@endslot

@endcomponent
<!-- <div class="card">
	<div class="card-header">
		<div class="level">
			<span class="flex"><h5><a href="{{route('profile', $profileUser->name)}}">{{$profileUser->name}}</a> 
				published <a href="{{route('thread', [$activity->subjectable->channel->slug, $activity->subjectable->id])}}">{{$activity->subjectable->title}}</a>
			</h5></span>
			<span></span>
		</div>
	</div>

	<div class="card-body">                
		{{$activity->subjectable->body}}
	</div>	
</div> -->