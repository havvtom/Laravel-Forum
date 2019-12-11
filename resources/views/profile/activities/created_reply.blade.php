@component('profile.activities.activity')

	@slot('heading')
		<span class="flex"><h5><a href="{{route('profile', $profileUser->name)}}">{{$profileUser->name}}</a> 
				replied to <a href="{{route('thread', [$activity->subjectable->thread->channel->slug, $activity->subjectable->thread->id])}}">{{$activity->subjectable->thread->title}}</a>
			</h5></span>
	@endslot
	@slot('body')
		{{$activity->subjectable->body}}
	@endslot

@endcomponent






